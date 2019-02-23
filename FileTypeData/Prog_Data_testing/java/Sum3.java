package com.jason.leetcode;

import com.jason.tools.Interval;

import java.util.*;

/**
 * Medium
 * 3 Sum
 * Given an array S of n integers, are there elements a, b, c in S such that a + b + c = 0? Find all unique triplets in the array which gives the sum of zero.
 * Note:
 * Elements in a triplet (a,b,c) must be in non-descending order. (ie, a ≤ b ≤ c)
 * The solution set must not contain duplicate triplets.
 * https://leetcode.com/problems/3sum/
 * AC
 */
public class Sum3 {

    public static boolean log = false;


    /**
     * t
     * @param nums
     * @return
     */
    public List<List<Integer>> threeSum3(int[] nums) {
        List<List<Integer>> result = new ArrayList<List<Integer>>();
        if(nums==null || nums.length<3) return result;
        Arrays.sort(nums);
        for(int i=0;i<nums.length;i++){
            int low = i+1;
            int high = nums.length -1;
            while(low<high){
                if(nums[i]+nums[low]+nums[high]==0){
                    result.add(Arrays.asList(nums[i], nums[low], nums[high]));
                    while(i+1<nums.length && nums[i+1]==nums[i]) i++;
                    while(low+1<nums.length && nums[low]==nums[low+1]) low++;
                    while(high-1>=0 && nums[high]==nums[high-1]) high--;
                    low++;
                    high--;
                } else if(nums[i]+nums[low]+nums[high]>0) high--;
                else low++;
            }
        }
        return result;
    }


    /**
     * add each two nums,then find the third num
     *
     * @param nums
     * @return
     *
    public List<List<Integer>> threeSum2(int[] nums) {
        List<List<Integer>> result = new ArrayList<List<Integer>>();
        if(nums==null || nums.length<3) return result;
//        Arrays.sort(nums);
        List<List<Integer>> res = new LinkedList<>();



        HashMap<Two, Set<Three>> hm = new HashMap<>();
        int len = nums.length;
        for (int i = 0; i < len - 1; i++) {
//            if(i+1<len) {
            for (int j = i + 1; j < len; j++) {
                int third = -(nums[i] + nums[j]);
                Set<Three> tmp = hm.get(third);
                int ci,cj,posThird=-1;
                if(nums[i]<nums[j]){
                    if(third<nums[i]){
                        posThird = 0;
                    }else if(third>=nums[j]){
                        posThird = 2;
                    }else if(third>=nums[i] && third<nums[j]){
                        posThird = 1;
                    }
                    ci= i;
                    cj = j;
                }else{
                    if(third<nums[j]){
                        posThird = 0;
                    }else if(third>=nums[i]){
                        posThird = 2;
                    }else if(third>=nums[j] && third<nums[i]){
                        posThird = 1;
                    }
                    ci = j;
                    cj = i;
                }
                Two tw = new Two(nums[ci],nums[cj],ci,cj);
                Three tr = new Three(tw,third,posThird);
//                 c = new Container(ci, cj, nums[ci],nums[cj],third,posThird);
                if (tmp == null) {
                    tmp = new HashSet<Three>();
                    hm.put(tw,tmp);
                }
                if (!tmp.contains(tw)) {
                    tmp.add(tr);
                }
            }
//            }else{
//                break;
//            }
        }
        if(log){
            System.out.println(hm);
        }
        Iterator<Map.Entry<Two, Set<Three>>> it = hm.entrySet().iterator();
        while(it.hasNext()){
            Map.Entry<Two, Set<Three>> entry = it.next();
            Two tw = entry.getKey();
            Set<Three> trs = entry.getValue();
            for(int i=0;i<nums.length;i++){
                Iterator<Three> its = entry.getValue().iterator();
                while(its.hasNext()) {
                    Three tr = its.next();
                    if( tr.third == nums[i]){
                        if(tw.idxA != i && tw.idxB!=i) {
                            tr.thirdIdx = i;
                            List<Integer> tmp = new ArrayList<>(3);
                            if(cnt.thirdPos == 0){
                                tmp.add(0,nums[i]);
                                tmp.add(1,nums[cnt.indexA]);
                                tmp.add(2,nums[cnt.indexB]);
                            }else if(cnt.thirdPos == 1){
                                tmp.add(0,nums[cnt.indexA]);
                                tmp.add(1,nums[i]);
                                tmp.add(2,nums[cnt.indexB]);
                            }else if(cnt.thirdPos == 2){
                                tmp.add(0,nums[cnt.indexA]);
                                tmp.add(1,nums[cnt.indexB]);
                                tmp.add(2,nums[i]);
                            }
                        }
                    }
                }
                /*
                if(third == nums[i]){
                    Iterator<Container> its = entry.getValue().iterator();
                    while(its.hasNext()){
                        Container cnt = its.next();
                        if(cnt.indexA != i && cnt.indexB!=i){
                            cnt.indexC = i;
                            List<Integer> tmp = new ArrayList<>(3);
                            if(cnt.thirdPos == 0){
                                tmp.add(0,nums[i]);
                                tmp.add(1,nums[cnt.indexA]);
                                tmp.add(2,nums[cnt.indexB]);
                            }else if(cnt.thirdPos == 1){
                                tmp.add(0,nums[cnt.indexA]);
                                tmp.add(1,nums[i]);
                                tmp.add(2,nums[cnt.indexB]);
                            }else if(cnt.thirdPos == 2){
                                tmp.add(0,nums[cnt.indexA]);
                                tmp.add(1,nums[cnt.indexB]);
                                tmp.add(2,nums[i]);
                            }
                            if(log)
                                System.out.println(cnt);
                            res.add(tmp);
                        }
                    }
                    break;
                }
            }
        }
        if(log)
            System.out.println(res);
        return null;
    }
*/


    public static class Two{
        public int A;
        public int B;
        public int idxA;
        public int idxB;

        public Two(int a,int b,int idxA,int idxB){
            this.A = a;
            this.B = b;
            this.idxA = idxA;
            this.idxB = idxB;

        }

        @Override
        public boolean equals(Object o) {
            if (this == o) return true;
            if (o == null || getClass() != o.getClass()) return false;

            Two two = (Two) o;

            if ((A == two.A && B == two.B) ||
                    (A == two.B) && (B == two.A))
                return true;
            return false;
        }

        @Override
        public int hashCode() {
            int result = A + B;
            return result;
        }
    }

    public static class Three{
        public Two t;
        public int third;

        public int thirdPos;
        public int thirdIdx;
        public Three(Two t,int third,int thirdPos){
            this.t = t;
            this.third = third;
            this.thirdPos = thirdPos;
        }

        @Override
        public boolean equals(Object o) {
            if (this == o) return true;
            if (o == null || getClass() != o.getClass()) return false;

            Three three = (Three) o;

            if (third != three.third) return false;
            return !(t != null ? !t.equals(three.t) : three.t != null);

        }

        @Override
        public int hashCode() {
            int result = t != null ? t.hashCode() : 0;
            result = 31 * result + third;
            return result;
        }
    }


    public static class Container {
        public int indexA;
        public int indexB;
        public int indexC;
        public int A;
        public int B;
        public int third;
        public int thirdPos;

        public Container() {
            indexC = -1;
        }

        public Container(int ia, int ib,int a,int b,int third ,int tp) {
            this.indexA = ia;
            this.indexB = ib;
            this.A = a;
            this.B = b;
            indexC= -1;
            this.third = third;
            this.thirdPos = tp;
        }

        @Override
        public boolean equals(Object o) {
            if (this == o) return true;
            if (o == null || getClass() != o.getClass()) return false;

            Container container = (Container) o;

            if ((A == container.A && B == container.B) ||
                    (A == container.B) && (B == container.A))
                return true;
            return false;
        }

        @Override
        public int hashCode() {
            int result = 31 * (A + B);
//            result = 31 * result + third;
            return result;
        }

        @Override
        public String toString() {
            return "Container{" +
                    "indexA=" + indexA +
                    ", indexB=" + indexB +
                    ", indexC=" + indexC +
                    ", A=" + A +
                    ", B=" + B +
                    ", third=" + third +
                    ", thirdPos=" + thirdPos +
                    '}';
        }
    }



    /**
     * three nest loops
     *
     * @param nums
     * @return
     */
    public List<List<Integer>> threeSum1(int[] nums) {
        List<List<Integer>> res = new LinkedList<>();
        if (nums == null) {
            return res;
        }
        int len = nums.length;
        if (len <= 1) {
            return res;
        }
        List<Set<Integer>> sets = new LinkedList<>();
        int a, b, c;
        for (int i = 0; i < len; i++) {
            a = nums[i];
            if (i + 1 < len) {
                for (int j = i + 1; j < len; j++) {
                    b = nums[j];
                    if (j + 1 < len) {
                        for (int k = j + 1; k < len; k++) {
                            c = nums[k];
                            if (a + b + c == 0) {
                                Set<Integer> tmp = new HashSet<>();
                                tmp.add(a);
                                tmp.add(b);
                                tmp.add(c);
                                if (!sets.contains(tmp)) {
                                    sets.add(tmp);
                                    List<Integer> tmpl = new LinkedList<>();
                                    tmpl.add(a);
                                    tmpl.add(b);
                                    tmpl.add(c);
                                    res.add(tmpl);
                                }

                            }
                        }
                    }
                }
            }
        }
        return res;
    }


}
