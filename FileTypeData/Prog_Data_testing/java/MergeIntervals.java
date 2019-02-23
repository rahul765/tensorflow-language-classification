package com.jason.leetcode;

import com.jason.tools.Interval;

import java.util.Collections;
import java.util.Comparator;
import java.util.List;

/**
 * Hard
 * Merge Intervals
 * https://leetcode.com/problems/merge-intervals/
 *
 Given indexA collection of intervals, merge all overlapping intervals.
 For example,
 Given [1,3],[2,6],[8,10],[15,18],
 return [1,6],[8,10],[15,18].

 AC Runtime: 432 ms
 */
public class MergeIntervals {
//	private static final boolean log = false;
	private static final boolean log = true;

	private static void print(List<Interval> l){
		Interval.printInterval(l);
	}
	private class Ivc implements Comparator<Interval> {
		@Override
		public int compare(Interval o1, Interval o2) {
			return o1.start-o2.start;
		}
	}

	public List<Interval> merge(List<Interval> intervals) {
		if(intervals == null || intervals.size()==0 || intervals.size() ==1 ){
			return intervals;
		}
		if(intervals.size()==2){
			Interval res = merge(intervals.get(0),intervals.get(1));
			if(res!=null ){
				intervals.removeAll(intervals);
				intervals.add(res);
			}
		}else{
			Collections.sort(intervals, new Ivc());
			if(log) {
				System.out.println("af sort");
				print(intervals);
			}
			Interval insert = intervals.remove(0);
			int len = intervals.size();
			for(int i=0;i<len;i++){
				if(log) {
					System.out.println("i:"+i+",len:"+len+",insert:" + insert);
					print(intervals);
				}

				/**
				 * insert
				 * now next
				 *
				 * insert merge now -> res
				 * case res not null:
				 * 	now <- res
				 *  del now
				 *  	res.end >= next.start //need merge
				 *  		remove now from list
				 *  		insert <- now
				 *  	res.end < next.start //no need merge
				 *  		insert <- next
				 *  		remove next
				 * case res null:
				 * 	insert <-> now
				 */
				Interval now = intervals.get(i);
				Interval next = null;

				if(i+1<=intervals.size()-1){
					next = intervals.get(i+1);
				}
				Interval res = merge(insert, now);
				if(res !=null){
					now.start = res.start;
					now.end = res.end;
					if(next!=null) {
						if (res.end >= next.start) {//next need merge now
							insert.start = res.start;
							insert.end = res.end;
							intervals.remove(now);
							if (log) {
								System.out.println("af remove " + i + ",insert " + insert);
								print(intervals);
							}
							i--;
							len--;
						} else {
							insert.start = next.start;
							insert.end = next.end;
							intervals.remove(next);
							if (log) {
								System.out.println("af remove next " + (i + 1) + ",insert " + insert);
								print(intervals);
							}
							len--;
						}
					}else{
						if(log)
							System.out.println("next null");
						intervals.remove(now);
						insert.start = res.start;
						insert.end = res.end;
					}
				}else{
					exchange(insert, now);
				}
			}
			if(intervals.size()>1){
				Interval last = intervals.get( intervals.size()-1 );
				if(insert.start > last.end ){
					intervals.add(insert);
				}else if(insert.start == last.end){
					last.end = insert.end;
				}else{//last.end < insert.start
					Interval res = merge(last,insert);
					if(res!=null){
						last.start = res.start;
						last.end = res.end;
					}else {
						intervals.add(intervals.size() - 1, insert);
					}
				}
			}else{
				intervals.add(insert);
			}
		}
		return intervals;
	}


	public void exchange(Interval iv1,Interval iv2){
		int tmp = iv1.start;
		iv1.start = iv2.start;
		iv2.start = tmp;
		tmp = iv1.end;
		iv1.end = iv2.end;
		iv2.end = tmp;
	}

	/**
	 *
	 * @param i1
	 * @param i2
	 * @return
	 */
	public Interval merge(Interval i1,Interval i2){

		Interval res = new Interval();
		/**
		 * 				st1  ed1
		 * st2  ed2
		 * 							st2  ed2
		 *
		 */
		if( ( i2.end <i1.start )|| (i1.end<i2.start) ){
			return null;
		}
		/**
		 * 			st1  	ed1
		 * 		st2  	ed2
		 * 		st2				ed2
		 */
		else if( i2.start <= i1.start ){
			res.start = i2.start;
			res.end = max(i1.end,i2.end);
		}
		/**
		 * 	st1 	ed1
		 * 		st2		ed2
		 */
		else if( i2.start>i1.start){
			res.start = i1.start;
			res.end = max(i1.end,i2.end);
		}
		if(log) {
			System.out.println("I1 " + i1 + ",I2 " + i2+"="+res);
		}
		return res;
	}

	public int max(int a,int b){
		return a>=b?a:b;
	}

	public static void main(String[] args) {
		//[1,3],[2,6],[8,10],[15,18]
//		int [] indexA = {1,3,2,6,8,10,15,18};
//		int [] indexA = {1,2,4,5};
//		int [] indexA = {1,2,2,3,3,4};
//		int [] indexA = {1,4,2,3,3,4};
//		int [] indexA = {1,5,2,3,3,4};
//		int [] indexA = {1,5,2,3,3,4};
//		int [] indexA = {1,4,0,2,3,5};
//		int [] indexA =  {2,3,2,2,3,3,1,3,5,7,2,2,4,6};
		int[] a = {0,2,2,3,4,4,0,1,5,7,4,5,0,0};
		List<Interval> l = Interval.initList(a);
//		Interval.printInterval(l);
		MergeIntervals m = new MergeIntervals();
		List<Interval> res = m.merge(l);
		Interval.printInterval(res);
	}
}
