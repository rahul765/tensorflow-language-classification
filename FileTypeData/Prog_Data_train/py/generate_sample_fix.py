import sys
import os
import io
import random as r
import string


def usage():
    print("Usage: %s <file_name> <num_of_msg>" % os.path.basename(__file__))


def random_word(length):
    return ''.join(r.choice(string.lowercase) for i in range(length))


def create_tag():
    return "%d=%s" % (r.randint(1, 999), random_word(r.randint(1, 30)))


if __name__ == "__main__":
    if len(sys.argv) != 3:
        print sys.argv
        usage()
        sys.exit(-1)

    file_name = sys.argv[1]
    num_of_msg = int(sys.argv[2])

    print("Supplied file name: " + file_name)
    print ("Wanted message number: " + str(num_of_msg))

    delimiter = bytearray([1])
    newline = bytearray([0x0a])

    with io.open(file_name, "wb") as f:
        for i in range(num_of_msg):
            msg_len = r.randint(5, 30)
            for j in range(msg_len):
                f.write(create_tag())
                f.write(delimiter)
            f.write(newline)

    print("File generated!")
