import os


# rename file function
def rename_files():
    # get files in directory
    file_list = os.listdir(r"./prank")
    current_path = os.getcwd()  # get current working directory
    os.chdir(r"./prank")  # change working directoru

    # rename files
    for filename in file_list:
        new_filename = filename.translate(None, "0123456789") # takes away numbers from the file
        os.rename(filename, new_filename)
        print("File changed from " + filename + " to " + new_filename)
    os.chdir(current_path)


rename_files()
