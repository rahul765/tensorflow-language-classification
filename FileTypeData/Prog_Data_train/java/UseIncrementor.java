public class UseIncrementor{
 
 public static void main(String[] args){
  
   Incrementor count1 = new Incrementor();
   Incrementor count2 = new Incrementor(100);

   System.out.println("Five Values of Count1 :");
   countFiveTimes(count1);

   System.out.println("Five Values of Count2 :");
   countFiveTimes(count2);

   System.out.println("Five new Values of Count1 :");
   countFiveTimes(count1);

 }

 private static void countFiveTimes(Incrementor count){
  for(int i= 0 ; i < 5 ;i++){
   System.out.println("-> "+ count.nextValue());
  }
  return;
 }
}
