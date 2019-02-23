import turtle


def drawPic():
    # create a window with an orange background
    window = turtle.Screen()
    window.bgcolor("red")

    # square shape
    sq = turtle.Turtle()
    sq.shape("turtle")
    sq.color("yellow")
    sq.speed(2)

    for x in range(1, 25):
        for i in range(1,5):
            # side
            sq.forward(100)
            sq.right(90)
        # turn to new angle
        sq.right(10)

    window.exitonclick()


drawPic()
