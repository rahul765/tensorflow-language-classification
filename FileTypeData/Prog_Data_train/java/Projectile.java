package piotrowski.jakub.java.games.kiloboltgame;

public class Projectile
{
	private int x, y, speedX;
	private boolean visible;
	
	public Projectile(int x, int y)
	{
		this.x = x;
		this.y = y;
		speedX = 7;
		visible = true;
	}
	
	public void update()
	{
		x += speedX;
		if(x > 800)
			visible = false;
	}

	// Getters and Setters
	
	public int getX()
	{
		return x;
	}

	public void setX(int x)
	{
		this.x = x;
	}

	public int getY()
	{
		return y;
	}

	public void setY(int y)
	{
		this.y = y;
	}

	public int getSpeedX()
	{
		return speedX;
	}

	public void setSpeedX(int speedX)
	{
		this.speedX = speedX;
	}

	public boolean isVisible()
	{
		return visible;
	}

	public void setVisible(boolean visible)
	{
		this.visible = visible;
	}
}
