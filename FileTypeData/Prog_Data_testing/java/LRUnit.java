package com.jason.tools;
//import java.text.SimpleDateFormat;
import java.util.Date;

/**
 * v5,date change to long
 * v4,value,date
 * v3,value,count
 */
public class LRUnit{
	private int value;
	//private int used_count;
	//private Date used_date;
	private long  used_date;
	
	public LRUnit(int value,long dt) {
		this.value = value;
		//this.used_count = 0;
		this.used_date = dt;
	}
	
	public void setNew(int value){
		this.value = value;
		//this.used_count = 0;
		this.used_date = System.nanoTime();//new Date();
	}
	
	public long getUsed_date() {
		return used_date;
	}

	public void setUsed_date(long used_date) {
		this.used_date = used_date;
	}

	public void flushDate(){
		this.used_date = System.nanoTime();//new Date();
	}
	
//	public void addCount(){
//		used_count++;
//	}
	
	@Override
	public String toString() {
		//SimpleDateFormat df = new SimpleDateFormat("HH:mm:ss");//设置日期格式
		//return "[value=" + value + ", used_count=" + used_count + ",t:"+used_date.getTime() +"]";
		return "[value=" + value + ",t:"+used_date +"]";
	}
	public int getValue() {
		return value;
	}
	public void setValue(int value) {
		this.value = value;
	}
}



//	public int getUsed_count() {
//		return used_count;
//	}
//	public void setUsed_count(int used_count) {
//		this.used_count = used_count;
//	}
	
	
//}
/**
 * v1,key value
 * @author Jason Liu
 *
 *
public class LRUnit {
	private int key;
	private int value;
	
	private int used_count;
	//private boolean setValue;
	//private Date used_date;
	
	public int getKey() {
		return key;
	}

	public void setKey(int key) {
		this.key = key;
	}

//	public Date getUsed_date() {
//		return used_date;
//	}

//	public void setUsed_date(Date used_date) {
//		this.used_date = used_date;
//	}

	public LRUnit(int key,int value){//,Date dt){
		this.key = key;
		//this.setValue = false;
		this.value = value;
		this.used_count = 0;
		//this.used_date = dt;
		
	}
	
	public void setNew(int key,int value){
		this.key = key;
		//this.setValue = false;
		this.value = value;
		this.used_count = 0;
		//this.used_date = dt;
		
	}
	
	public void cleanUnit(){
		key = -1;
		value = -1;
		used_count=0;
		//setValue=false;
	}
	
	public void addCount(){
		used_count++;
	}
	
//	public void flushDate(){
//		//this.used_date = new Date();
//	}
	
	public int getValue() {
		
		return value;
	}



	public void setValue(int value) {
		this.value = value;
	}



	public int getUsed_count() {
		return used_count;
	}



	public void setUsed_count(int used_count) {
		this.used_count = used_count;
	}

	
	@Override
	public String toString() {
		return "LRUnit [key=" + key + ", value=" + value + ", used_count="
				+ used_count + "]";
	}



//	public boolean isSetValue() {
//		return setValue;
//	}



//	public void setSetValue(boolean gotValue) {
//		this.setValue = gotValue;
//	}

//	@Override
//	public String toString() {
//		SimpleDateFormat df = new SimpleDateFormat("HH:mm:ss");//设置日期格式
//		//return "[key=" + key + ", value=" + value + ", used_count="+ used_count + ", setValue=" + setValue + ", used_date="
//				+ used_date.getTime() + "]";
//	}



//	public String toString(){
//		return "["+value+","+used_count+""+"]";
//	}
}
*/