import random
import numpy as np

STATE_GAME_OVER = 3
MAX_PADDLE_X_VEL = 5

NUM_PADDLE_X_STATES = 32
NUM_BALL_X_STATES = 32
NUM_BALL_Y_STATES = 4
NUM_BALL_DIR_STATES = 2
#NUM_BALL_SPEED_STATES = 8
#NUM_BRICK_STATES = 16
TOTAL_ACTIONS = 11
TOTAL_STATES = NUM_PADDLE_X_STATES*NUM_BALL_X_STATES*NUM_BALL_Y_STATES*NUM_BALL_DIR_STATES
print "total states: "+str(TOTAL_STATES)
print "q matrix entries: "+str(TOTAL_STATES*TOTAL_ACTIONS)

class AI():
    def __init__(self,model):
        self.q_matrix = np.zeros((TOTAL_STATES,TOTAL_ACTIONS))
        self.last_action = 5
        self.last_state = self.convert_model_info_to_state(model)
        self.random_move_frequency = 0
        self.ALPHA = 0.7
        self.LAMBDA = 0.999

    def get_random_next_move(self):
        return random.randint(0,5)

    def follow_ball(self,model):
        paddle_x = model.paddle.left + model.paddle_width/2
        ball_x = model.ball.left + model.ball_diameter/2
        dist_to_ball = ball_x - paddle_x
        paddle_x_vel = dist_to_ball

        try: direction_to_ball = dist_to_ball/abs(dist_to_ball)
        except: direction_to_ball = 0 #catch divide by zero error
        paddle_x_magnitude = min(abs(dist_to_ball/5),MAX_PADDLE_X_VEL)
        paddle_x_vel = direction_to_ball*paddle_x_magnitude
        model.paddle.left += paddle_x_vel

    def update_q(self,model,reward,new_state):
        q = self.q_matrix
        q[self.last_state,self.last_action] += self.ALPHA*(reward+self.LAMBDA*self.find_max_reward(q,new_state)-q[self.last_state,self.last_action])
        #print str(np.count_nonzero(q)) + "/" + str(TOTAL_STATES)

    def make_qlearn_move(self,model):
        q = self.q_matrix
        game_state = self.convert_model_info_to_state(model)
        self.last_state = game_state
        if random.random < self.random_move_frequency:
            random_action = random.randint(0,10)
            self.make_best_action(random_action,model)
            self.last_action = random_action
        else:
            best_action_list = self.find_best_action(q,game_state)
            best_action = random.choice(best_action_list)
            self.make_best_action(best_action,model)
            self.last_action = best_action

    def make_best_action(self,best_action,model):
        best_paddle_vel = best_action - 5
        model.paddle.left += best_paddle_vel
        self.last_action = best_action

    def observe_reward(self,model):
        reward = self.score_model(model)
        new_state = self.convert_model_info_to_state(model)
        return reward,new_state

    def score_model(self,model):
        if model.state == STATE_GAME_OVER:
            return -10000
        else:
            #paddle_x = model.paddle.left + model.paddle_width/2
            #ball_x = model.ball.left + model.ball_diameter/2
            #dist_to_ball = abs(ball_x - paddle_x)
            #reward = (1.0/(dist_to_ball+1))*10000
            #reward = -dist_to_ball
            #reward += model.score_change
            #print reward
            #reward = model.score_change
            reward = -4+model.score_change
            #return -1
            return reward

    def find_max_reward(self,q,game_state):
        state_row = q[game_state,:]
        return state_row[state_row.argmax()]

    def find_best_action(self,q,game_state):
        """returns int between 0 and 10"""
        state_row = q[game_state,:]
        max_reward = [-99999999999999999999999999999]
        max_reward_indices = [None]
        for i, reward in enumerate(state_row):
            if reward > max_reward[0]:
                max_reward = [reward]
                max_reward_indices = [i]
            elif reward == max_reward[0]:
                max_reward_indices.append(i)
        return max_reward_indices

    def convert_model_info_to_state(self,model):
        #brick_state = self.get_brick_state(model)
        paddle_state = self.get_paddle_state(model)
        ball_state = self.get_ball_state(model)
        #brick_and_paddle_state = brick_state*NUM_PADDLE_X_STATES+paddle_state
        #game_state = brick_and_paddle_state*NUM_BALL_X_STATES*NUM_BALL_Y_STATES + ball_state
        game_state = paddle_state*NUM_BALL_X_STATES*NUM_BALL_Y_STATES*NUM_BALL_DIR_STATES + ball_state
        return game_state

    def get_paddle_state(self,model):
        """returns int between 0 and 7 that encodes location of paddle"""
        paddle_x = model.paddle.left + model.paddle_width/2
        screen_width,screen_height = model.SCREEN_SIZE
        divisions = NUM_PADDLE_X_STATES
        division_size = int((1.0*screen_width)/divisions)
        paddle_state = paddle_x/division_size
        return paddle_state

    def get_ball_state(self,model):
        """returns int between 0 and 255 that encodes location of the ball"""
        ball_x = model.ball.left + model.ball_diameter/2
        ball_y = model.ball.top + model.ball_diameter/2
        ball_moving_left = (model.ball_vel[0] == abs(model.ball_vel[0]))
        if ball_moving_left:
            ball_dir = 0
        else:
            ball_dir = 1
        #ball_speed = abs(model.ball_vel[0])-1 #ball speed between 0 and 7
        screen_width,screen_height = model.SCREEN_SIZE
        x_divisions = NUM_BALL_X_STATES
        y_divisions = NUM_BALL_Y_STATES
        x_division_size = int((1.0*screen_width)/x_divisions)
        y_division_size = int((1.0*screen_width)/y_divisions)
        ball_x_state = ball_x/x_division_size
        ball_y_state = ball_y/y_division_size
        ball_position_state = x_divisions*ball_y_state+ball_x_state
        ball_state = ball_dir*x_divisions*y_divisions+ball_position_state
        return ball_state
        #final_ball_state = ball_dir_and_position_state + ball_speed*x_divisions*y_divisions*NUM_BALL_DIR_STATES
        #return final_ball_state

    def get_brick_state(self,model):
        """returns int between 0 and 15 that encodes whether or not there
        are bricks present in one of the 4 discrete columns (2^4 is 16)"""
        brick_cols = model.brick_cols
        brick_col_binary = [None,None,None,None]
        for i, col in enumerate(brick_cols):
            brick_col_binary[i] = (len(brick_cols[i]) > 0)
        brick_state = 0
        for j, val in enumerate(brick_col_binary):
            brick_state += (2**j)*brick_col_binary[j]
        return brick_state


