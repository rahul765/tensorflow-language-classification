import sys
import os


def usage():
    print("Usage: %s <file1> <file2> [<batch_size>]" % os.path.basename(__file__))


if __name__ == "__main__":
    if len(sys.argv) < 3:
        usage()
        sys.exit(-1)

    file1 = sys.argv[1]
    file2 = sys.argv[2]

    if not os.path.isfile(file1) or not os.path.isfile(file2):
        print("Please provide real file paths")
        sys.exit(-1)

    batch_size = 10
    if len(sys.argv) > 3:
        batch_size = int(sys.argv[3])

    with open(file1, 'r') as f1, open(file2, 'r') as f2:
        pass

