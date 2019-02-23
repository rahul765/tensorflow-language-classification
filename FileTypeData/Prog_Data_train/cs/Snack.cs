using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace HungrySnack
{
    enum Direction { 
        INVALID,
        UP,
        DOWN,
        LEFT,
        RIGHT,
    };

    enum Situation
    {
        NORMAL,
        DIE,
        EATFOOD,
    };

    class Snack: Sprite
    {
        public Head head;
        List<Sprite> bodyList;

        public Snack()
        {
            head = new Head();
            head.setPosition(1,3);
            head.nowDirection = Direction.RIGHT ;

            bodyList = new List<Sprite>();
            Body body = new Body();
            body.setPosition(1,2);
            bodyList.Add(body);
            Body body2 = new Body();
            body2.setPosition(1,1);
            bodyList.Add(body2);
        }

        //长长一格
        public void AddLength()
        {
            GamePoint nextPoint = new GamePoint();
            GamePoint prePoint = new GamePoint();
            nextPoint = head.position;
            prePoint.setPointXY(head.position.posX, head.position.posY);

            switch (head.nowDirection)
            {
                case Direction.UP: nextPoint.posX -= 1;
                    break;
                case Direction.DOWN: nextPoint.posX += 1;
                    break;
                case Direction.LEFT: nextPoint.posY -= 1;
                    break;
                case Direction.RIGHT: nextPoint.posY += 1;
                    break;
            }

            Head newhead = new Head();
            newhead.position = nextPoint;
            newhead.nowDirection = head.nowDirection;
            Body newbody = new Body();
            newbody.position = prePoint;
            head = newhead;
            bodyList.Insert(0, newbody);
        }
        //贪吃蛇往面前移动一个
        public void SnackMove()
        {
            GamePoint nextPoint = new GamePoint();
            GamePoint prePoint = new GamePoint();
            nextPoint = head.position;
            prePoint.setPointXY(head.position.posX,head.position.posY);

            switch (head.nowDirection)
            {
                case Direction.UP: nextPoint.posX -= 1;
                    break;
                case Direction.DOWN: nextPoint.posX += 1;
                    break;
                case Direction.LEFT: nextPoint.posY -= 1;
                    break;
                case Direction.RIGHT: nextPoint.posY += 1;
                    break;
            }

            Head newhead = new Head();
            newhead.position = nextPoint;
            newhead.nowDirection = head.nowDirection;
            Body newbody = new Body();
            newbody.position = prePoint;
            head = newhead;
            bodyList.Insert(0, newbody);
            bodyList.RemoveAt(bodyList.Count()-1);
        }

        public void ChangeDirection(Direction dir)
        {
            head.nowDirection = dir;
        }
        override public void Print()
        {
            head.Print();
            foreach (Sprite b in bodyList) 
            {
                b.Print();
            }
        }

        public override void SetToLogicBoard(SpriteType[,] logicBoard)
        {
            head.SetToLogicBoard(logicBoard);
            foreach(Sprite b in bodyList)
            {
                b.SetToLogicBoard(logicBoard);
            }
        }
    }
}
