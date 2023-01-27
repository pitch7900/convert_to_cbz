#!/usr/bin/python3
# Special version of converter for webservice without verbose output and no renaming of cbz files
# # pip install Pillow  rarfile python-magic colored PyMuPDF

# For windows we need poppler  https://github.com/oschwartz10612/poppler-windows/releases/
# install it and add the Library\bin folder to path
# Sous Windows python-magic-bin
# Sous Linux sudo apt-get install libmagic1

import os
import shutil
import zipfile
import rarfile
import sys
import tempfile
import magic
import PIL.Image
import fitz

def convert_to_jpeg(image_filename):
    """
    It opens an image, saves it as a JPEG, and then deletes the original image
    
    :param image_filename: The name of the image file to convert
    """
    # Ouvrir l'image avec PIL
    with PIL.Image.open(image_filename) as image:
        # Enregistrer l'image au format JPEG
        image.save(os.path.splitext(image_filename)[0] + '.jpg', format='JPEG')
        # Supprimer l'image d'origine
        os.remove(image_filename)

def convert_all_files_to_jpeg(path):
    """
    It takes a path to a directory, and then for each file in that directory, if the file is an image,
    it converts it to a jpeg
    
    :param path: The path to the directory containing the files you want to convert
    """
    for dirpath, subdirs, files in os.walk(path):
        for file in files:
            if os.path.splitext(file)[1] in ('.png', '.gif', '.bmp'):
                convert_to_jpeg(file)

def cbz_to_cbz(cbz_source,cbz_destination):
    """
    It takes a CBZ file, extracts all the images, converts them to JPEG, renames them to a 4 digit
    number, and then creates a new CBZ file with the renamed images
    
    :param cbz_source: The source CBZ file
    :param cbz_filename: The name of the CBZ file to create
    """
    # Créer un répertoire temporaire pour les images
    temp_dir = tempfile.mkdtemp()
    

    with zipfile.ZipFile(cbz_destination, 'w') as cbz_file:
        with zipfile.ZipFile(cbz_source, 'r') as source:
            # It extracts all the files from the source zip file into the temporary directory.
            source.extractall(temp_dir)

        
        # It converts all the images in the temporary directory to JPEG format.
        convert_all_files_to_jpeg(temp_dir)

        i = 1
        for dirpath, subdirs, files in os.walk(temp_dir):
            # Réindexer les fichiers jpeg
            files.sort()
            for file in (files):
                if file.endswith('.jpg'):
                    old_path = os.path.join(dirpath, file)
                    new_filename = str(i).zfill(4) + ".jpg"
                    new_path = os.path.join(dirpath,new_filename)
                    os.rename(old_path, new_path)
                    # Ajouter le fichier renommé au fichier CBZ
                    cbz_file.write(new_path,new_filename)
                    i += 1

        # Supprimer les fichiers extraits
        shutil.rmtree(temp_dir)

def pdf_to_cbz(pdf_filename, cbz_filename):
    """
    It takes a PDF file and converts it to a CBZ file
    
    :param pdf_filename: The path to the PDF file you want to convert
    :param cbz_filename: The name of the CBZ file to create
    """
    # Créer un répertoire temporaire pour les images
    temp_dir = tempfile.mkdtemp()
    with zipfile.ZipFile(cbz_filename, 'w') as cbz_file:
        # Convertir les pages en images et les enregistrer dans le répertoire temporaire
        mat = fitz.Matrix(300 / 72, 300 / 72)  # sets zoom factor for 300 dpi
        pages = fitz.open(pdf_filename)
        i = 1
        for page in (pages):
            pix = page.get_pixmap(matrix=mat)
            new_png_filename = str(i).zfill(4) + ".png"
            new_jpeg_filename= str(i).zfill(4) + ".jpg"
            new_png_filepath = os.path.join(temp_dir, new_png_filename)
            new_jpeg_filepath = os.path.join(temp_dir, new_jpeg_filename)
            pix.save(new_png_filepath)
            convert_to_jpeg(new_png_filepath)
            i += 1
            cbz_file.write(new_jpeg_filepath,new_jpeg_filename)

        # Supprimer les fichiers extraits
        shutil.rmtree(temp_dir)
    




def cbr_to_cbz(cbr_filename, cbz_filename):
    """
    It takes a CBR file and creates a CBZ file from it
    
    :param cbr_filename: The name of the CBR file to convert
    :param cbz_filename: The name of the CBZ file to be created
    """
     # Créer un répertoire temporaire pour extraire les fichiers
    temp_dir = tempfile.mkdtemp()
    # Créer un fichier ZIP vide
    with zipfile.ZipFile(cbz_filename, 'w') as cbz_file:
        # Extraire tous les fichiers du fichier CBR
        with rarfile.RarFile(cbr_filename, 'r') as cbr_file:
            cbr_file.extractall(temp_dir)

        # It converts all the images in the temporary directory to JPEG format.
        convert_all_files_to_jpeg(temp_dir)

        i = 1
        for dirpath, subdirs, files in os.walk(temp_dir):
            # Réindexer les fichiers jpeg
            files.sort()
            for file in (files):
                if file.endswith('.jpg'):
                    old_path = os.path.join(dirpath, file)
                    new_filename = str(i).zfill(4) + ".jpg"
                    new_path = os.path.join(dirpath,new_filename)
                    os.rename(old_path, new_path)
                    # Ajouter le fichier renommé au fichier CBZ
                    cbz_file.write(new_path,new_filename)
                    i += 1

        # Supprimer les fichiers extraits
        shutil.rmtree(temp_dir)

# Vérifier si un nom de fichier est donné en argument
if len(sys.argv) != 2:
    print("Usage: {} FileToConvert".format(sys.argv[0]))
    sys.exit(1)

# Récupérer le nom de fichier source et le nom de fichier cible
source = sys.argv[1]
destination = os.path.splitext(source)[0] + '.cbz'


# A pattern matching statement. It is checking the file type of the source file and then calling the
# appropriate function.
m = magic.Magic(mime=True)


match (m.from_file(source)):
    case "application/x-rar":
        cbr_to_cbz(source,destination)
        print(destination)
        sys.exit(0)
    case "application/zip":
        cbz_to_cbz(source,destination)
        print(destination)
        sys.exit(0)
    case "application/pdf":
        pdf_to_cbz(source,destination)
        print(destination)
        sys.exit(0)
    case _:
        print("File format not recognized")
        sys.exit(1)

