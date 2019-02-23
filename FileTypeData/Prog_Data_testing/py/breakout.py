"""
 bricka (a breakout clone)
 Developed by Leonel Machava <leonelmachava@gmail.com>
 Super heavily modified and put into MVC by Dennis & Fillippos

 http://codeNtronix.com
"""
import math
import pickle as p
import pygame
import random
from AI import AI

SCREEN_SIZE   = 480,480

# Object dimensions
BRICK_WIDTH   = 120
BRICK_HEIGHT  = 20
PADDLE_WIDTH  = 60
PADDLE_HEIGHT = 12
BALL_DIAMETER = 16
BALL_RADIUS   = BALL_DIAMETER / 2

MAX_PADDLE_X = SCREEN_SIZE[0] - PADDLE_WIDTH
MAX_BALL_X   = SCREEN_SIZE[0] - BALL_DIAMETER
MAX_BALL_Y   = SCREEN_SIZE[1] - BALL_DIAMETER

# Paddle Y coordinate
PADDLE_Y = SCREEN_SIZE[1] - PADDLE_HEIGHT - 10

# Color constants
BLACK = (0,0,0)
WHITE = (255,255,255)
BLUE  = (0,0,255)
BRICK_COLOR = (200,200,0)

# State constants
STATE_BALL_IN_PADDLE = 0
STATE_PLAYING = 1
STATE_WON = 2
STATE_GAME_OVER = 3

class BrickView:

    def __init__(self):
        pygame.init()
        self.screen = pygame.display.set_mode(SCREEN_SIZE)
        pygame.display.set_caption("bricka (a breakout clone by codeNtronix.com)")
        if pygame.font:
            self.font = pygame.font.Font(None,30)
        else:
            self.font = None

    def show_stats(self,model):
        if self.font:
            font_surface = self.font.render("SCORE: " + str(model.score) + " LIVES: " + str(model.lives), False, WHITE)
            self.screen.blit(font_surface, (150,5))

    def show_message(self,message):
        if message is None:
            return
        if self.font:
            size = self.font.size(message)
            font_surface = self.font.render(message,False, WHITE)
            x = (SCREEN_SIZE[0] - size[0]) / 2
            y = (SCREEN_SIZE[1] - size[1]) / 2
            self.screen.blit(font_surface, (x,y))

    def fill_screen(self,color):
        self.screen.fill(color)

    def draw_brick_paddle_ball(self,model):
        # Draw paddle
        pygame.draw.rect(self.screen, BLUE, model.paddle)
        # Draw ball
        pygame.draw.circle(self.screen, WHITE, (model.ball.left + BALL_RADIUS, model.ball.top + BALL_RADIUS), BALL_RADIUS)
        # Draw bricks
        for brick in model.bricks:
            pygame.draw.rect(self.screen, BRICK_COLOR, brick)

        self.show_stats(model)
        pygame.display.flip()

    def kill_game(self):
        pygame.quit()

class BrickModel:

    def __init__(self):
        self.lives = 1
        self.score = 0
        self.score_change = 0
        self.state = STATE_PLAYING
        self.paddle   = pygame.Rect(300,PADDLE_Y,PADDLE_WIDTH,PADDLE_HEIGHT)
        self.ball     = pygame.Rect(300,PADDLE_Y - BALL_DIAMETER,BALL_DIAMETER,BALL_DIAMETER)
        self.ball_vel = [5,-5]
        self.x_num_bricks = 4
        self.y_num_bricks = 4
        self.create_bricks(0,0,1,1,self.y_num_bricks,self.x_num_bricks)
        self.brick_width = BRICK_WIDTH
        self.brick_height = BRICK_HEIGHT
        self.paddle_width = PADDLE_WIDTH
        self.paddle_height = PADDLE_HEIGHT
        self.ball_diameter = BALL_DIAMETER
        self.SCREEN_SIZE = SCREEN_SIZE

    def reset_game(self):
        self.lives = 1
        self.score = 0
        self.score_change = 0
        self.state = STATE_PLAYING
        self.paddle   = pygame.Rect(300,PADDLE_Y,PADDLE_WIDTH,PADDLE_HEIGHT)
        self.ball     = pygame.Rect(300,PADDLE_Y - BALL_DIAMETER,BALL_DIAMETER,BALL_DIAMETER)
        ball_dir = random.choice([-1,1])
        ball_x_speed = random.choice([1,2,3,4,5])
        self.ball_vel = [ball_x_speed*ball_dir,-5]
        self.x_num_bricks = 4
        self.y_num_bricks = 4
        self.create_bricks(0,0,1,1,self.y_num_bricks,self.x_num_bricks)
        self.brick_width = BRICK_WIDTH
        self.brick_height = BRICK_HEIGHT
        self.paddle_width = PADDLE_WIDTH
        self.paddle_height = PADDLE_HEIGHT
        self.ball_diameter = BALL_DIAMETER
        self.SCREEN_SIZE = SCREEN_SIZE

    def create_bricks(self,i_x_ofs,i_y_ofs,x_spacing,y_spacing,y_num_bricks,x_num_bricks):
        y_ofs = i_y_ofs
        self.bricks = []
        self.brick_cols = [[],[],[],[]]
        for i in range(y_num_bricks):
            x_ofs = i_x_ofs
            for j in range(x_num_bricks):
                brick = pygame.Rect(x_ofs,y_ofs,BRICK_WIDTH,BRICK_HEIGHT)
                self.bricks.append(brick)
                self.brick_cols[j].append(brick)
                x_ofs += BRICK_WIDTH + x_spacing
            y_ofs += BRICK_HEIGHT + y_spacing

    def move_ball(self):
        self.ball.left += self.ball_vel[0]
        self.ball.top  += self.ball_vel[1]
        #check left and right wall collisions
        if self.ball.left <= 0:
            self.ball.left = 0
            self.ball_vel[0] = -self.ball_vel[0]
        elif self.ball.left >= MAX_BALL_X:
            self.ball.left = MAX_BALL_X
            self.ball_vel[0] = -self.ball_vel[0]
        #check top and bottom collisions? is the bottom collision check useless?
        if self.ball.top < 0:
            self.ball.top = 0
            self.ball_vel[1] = -self.ball_vel[1]

    def check_paddle_collisions(self):
        if self.paddle.left < 0:
            self.paddle.left = 0
        if self.paddle.left > MAX_PADDLE_X:
            self.paddle.left = MAX_PADDLE_X

    def handle_collisions(self):

        for brick in self.bricks:
            self.score_change = 0
            if self.ball.colliderect(brick):
                self.score += 3
                self.score_change = 3
                self.ball_vel[1] = -self.ball_vel[1]
                self.bricks.remove(brick)
                for brick_col in self.brick_cols:
                    if brick in brick_col:
                        brick_col.remove(brick)
                break #so you can only break one brick at once
        #if len(self.bricks) == 0:
        #    self.state = STATE_WON

        if self.ball.colliderect(self.paddle):
            ball_x = self.ball.left + BALL_DIAMETER/2
            paddle_x = self.paddle.left + PADDLE_WIDTH/2
            dist_along_paddle = 1.0*(ball_x - paddle_x)
            #if (ball_x - paddle_x) == 0:
            #    direction_to_bounce = 0
            #else:
            #    direction_to_bounce = (ball_x - paddle_x)/abs(ball_x - paddle_x)
            self.ball.top = PADDLE_Y - BALL_DIAMETER
            self.ball_vel[1] = -self.ball_vel[1]
            self.ball_vel[0] = math.ceil(dist_along_paddle/5.1)
            if self.ball_vel[0] == 0:
                self.ball_vel[0] = random.choice([1,-1])
            #if (ball_x - paddle_x) == 0:
            #    self.ball_vel[0] = random.choice([0.2,-0.2])
            #    #never allow the paddle to hit the ball straight
            #else:
            #    self.ball_vel[0] = abs(self.ball_vel[0])*direction_to_bounce

        elif self.ball.top > self.paddle.top:
            self.lives -= 1
            if self.lives > 0:
                self.state = STATE_BALL_IN_PADDLE
            else:
                self.state = STATE_GAME_OVER
        self.check_paddle_collisions()

    def check_states(self):
        display_msg = None
        if self.state == STATE_PLAYING:
            self.move_ball()
            self.handle_collisions()
        elif self.state == STATE_BALL_IN_PADDLE:
            self.ball.left = self.paddle.left + self.paddle.width / 2
            self.ball.top  = self.paddle.top - self.ball.height
            display_msg = "PRESS SPACE TO LAUNCH THE BALL"
        elif self.state == STATE_GAME_OVER:
            display_msg = "GAME OVER. PRESS ENTER TO PLAY AGAIN"
        elif self.state == STATE_WON:
            display_msg = "YOU WON! PRESS ENTER TO PLAY AGAIN"
        return display_msg, (self.state == STATE_GAME_OVER) #or self.state == STATE_WON)

class BrickController():

    def __init__(self,model,ai=None):
        if ai is None:
            self.ai = AI(model)
        else:
            self.ai = ai

    def dec_learn_rate(self,learn_dec):
        self.ai.ALPHA = max(0,self.ai.ALPHA-learn_dec)

    def save_ai(self,file_name = "trained_ai.p"):
        p.dump(self.ai, open(file_name,"wb"))

    def ai_update_model(self,model):
        #self.ai.follow_ball(model)
        self.ai.make_qlearn_move(model)

    def controller_update_model(self,model):
        keys = pygame.key.get_pressed()

        if keys[pygame.K_LEFT]:
            model.paddle.left -= 5
            if model.paddle.left < 0:
                model.paddle.left = 0

        if keys[pygame.K_RIGHT]:
            model.paddle.left += 5
            if model.paddle.left > MAX_PADDLE_X:
                model.paddle.left = MAX_PADDLE_X

        if keys[pygame.K_SPACE] and model.state == STATE_BALL_IN_PADDLE:
            model.ball_vel = [5,-5]
            model.state = STATE_PLAYING
        elif keys[pygame.K_RETURN] and (model.state == STATE_GAME_OVER or model.state == STATE_WON):
            self.init_game()

class BrickGame():

    def __init__(self):
        self.m = BrickModel()
        self.v = BrickView()
        self.c = BrickController(self.m)
        self.clock = pygame.time.Clock()

    def run_game(self):
        game_over = False
        while not game_over:
            for event in pygame.event.get():
                if event.type == pygame.QUIT:
                    self.v.kill_game()

            self.clock.tick(50)
            self.v.fill_screen(BLACK)
            self.c.controller_update_model(self.m)
            display_msg,game_over = self.m.check_states()
            #check_states() updates the model based on move made by AI
            self.v.show_message(display_msg)
            self.v.draw_brick_paddle_ball(self.m)
        self.v.kill_game()

    def train_ai(self,ai_base_name):
        num_deaths = 0
        self.m.game_stuck_counter = 0
        while True:
            for event in pygame.event.get():
                if event.type == pygame.QUIT:
                    self.c.save_ai(ai_base_name + "_final.p")
                    self.v.kill_game()
                elif self.c.ai.ALPHA == 0:
                    print "FINISHED"
            self.m.game_stuck_counter += 1
            #game_stuck_counter tells ai to stop when it is in endless loop
            #print self.m.game_stuck_counter
            #self.clock.tick(200)
            #self.v.fill_screen(BLACK)
            self.c.ai_update_model(self.m)
            display_msg,game_over = self.m.check_states()
            #check_states() updates the model based on move made by AI
            if self.m.game_stuck_counter > 10000:
                game_over = True
                STATE_GAME_OVER = 3
                self.m.state == STATE_GAME_OVER
                self.m.game_stuck_counter = 0
            reward,new_state = self.c.ai.observe_reward(self.m)
            self.c.ai.update_q(self.m,reward,new_state)
            #self.v.show_message(display_msg)
            #self.v.draw_brick_paddle_ball(self.m)
            if game_over:
                self.m.game_stuck_counter = 0
                num_deaths += 1
                #print num_deaths
                if num_deaths%10000 == 0:
                    ai_name = ai_base_name + str(num_deaths) + ".p"
                    self.c.save_ai(ai_name)
                    self.c.dec_learn_rate(0.05)
                    print "iteration "+str(num_deaths)
                    print "learning rate: "+str(self.c.ai.ALPHA)
                self.m.reset_game()

    def resume_training_ai(self,ai_file_name,random_move_rate):
        ai = p.load(open(ai_file_name,"rb"))
        self.c = BrickController(self.m,ai)
        ai.random_move_frequency = random_move_rate
        while True:
            for event in pygame.event.get():
                if event.type == pygame.QUIT:
                    self.c.save_ai()
                    self.v.kill_game()

            #self.clock.tick()
            #self.v.fill_screen(BLACK)
            self.c.ai_update_model(self.m)
            display_msg,game_over = self.m.check_states()
            #check_states() updates the model based on move made by AI
            reward,new_state = self.c.ai.observe_reward(self.m)
            self.c.ai.update_q(self.m,reward,new_state)
            #self.v.show_message(display_msg)
            #self.v.draw_brick_paddle_ball(self.m)
            if game_over:
                self.m.reset_game()

    def test_ai(self,ai_file_name):
        ai = p.load(open(ai_file_name,"rb"))
        ai.random_move_frequency = 0
        self.c = BrickController(self.m,ai)
        while True:
            for event in pygame.event.get():
                if event.type == pygame.QUIT:
                    self.v.kill_game()
            self.clock.tick(200)
            self.v.fill_screen(BLACK)
            self.c.ai_update_model(self.m)
            display_msg,game_over = self.m.check_states()
            #check_states() updates the model based on move made by AI
            self.v.show_message(display_msg)
            self.v.draw_brick_paddle_ball(self.m)
            if game_over:
                self.m.reset_game()

if __name__ == "__main__":
    b = BrickGame()
    #b.run_game()
    ai_base_name = "new_ai_1"
    #b.train_ai(ai_base_name)
    #b.resume_training_ai("trained_ai.p",0)
    b.test_ai("new_ai_1_final.p")
