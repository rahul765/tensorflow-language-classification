package com.example.activity;

import android.app.Activity;
import android.opengl.GLSurfaceView;
import android.os.Bundle;

import com.example.opengl.MyRenderer;
/**
 * 使用opengl绘制一个三角形
 * @author Samuel
 *
 */
public class TestOpenGL_triangle extends Activity{

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		// TODO Auto-generated method stub
		super.onCreate(savedInstanceState);
		GLSurfaceView glView=new GLSurfaceView(this);
		MyRenderer renderer=new MyRenderer();
		glView.setRenderer(renderer);
		setContentView(glView);
	}

}
