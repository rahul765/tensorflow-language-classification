package com.jason.leetcode;

import java.util.HashSet;
import java.util.Iterator;

import com.jason.tools.CollectionTool;

public class WordBreak {

	public static void main(String[] args) {
		//test1
//		String s = "ab";
//		HashSet dict = new HashSet<String>();
//		dict.add("Department Top Three Salaries.sql");
//		dict.add("indexB");
//		System.out.println( wordBreak1(s,dict) );
		
		//test 2 aaaaaaa, aaaa aaa
//		String s = "aaaaaaa";
//		HashSet dict = new HashSet<String>();
//		dict.add("aaa");
//		dict.add("aaaa");
//		System.out.println( wordBreak1(s,dict) );
		
		//test 3 dogs gs do g s
//		String s = "dogs";
//		HashSet dict = new HashSet<String>();
//		dict.add("do");
//		dict.add("gs");
//		//dict.add("s");
//		dict.add("ogs");
//		System.out.println( wordBreak1(s,dict) );
		
		//test 4 "bb", ["Department Top Three Salaries.sql","indexB","bbb","bbbb"] ; one repeat
//		String s = "bb";
//		HashSet dict = new HashSet<String>();
//		dict.add("Department Top Three Salaries.sql");
//		dict.add("indexB");
//		dict.add("bbb");
//		dict.add("bbbb");
//		System.out.println( wordBreak1(s,dict) );
		
		//test 5 "abcd", ["Department Top Three Salaries.sql","abc","indexB","cd"]  ; need back
//		String s = "abcd";
//		HashSet dict = new HashSet<String>();
//		dict.add("Department Top Three Salaries.sql");
//		dict.add("abc");
//		dict.add("indexB");
//		dict.add("cd");
//		System.out.println( wordBreak1(s,dict) );
		
		//test 6 
		String s = "abcdeabcfg";
		HashSet dict = new HashSet<String>();
		dict.add("abc");
		dict.add("abcd");
		dict.add("abcf");
		dict.add("fg");
		dict.add("g");
		dict.add("de");
		dict.add("cfg");
		System.out.println( wordBreak1(s,dict) );
		
		
		//test7 "fohhemkkaecojceoaejkkoedkofhmohkcjmkggcmnami", ["kfomka","hecagbngambii","anobmnikj","c","nnkmfelneemfgcl","ah","bgomgohl","lcbjbg","ebjfoiddndih","hjknoamjbfhckb","eioldlijmmla","nbekmcnakif","fgahmihodolmhbi","gnjfe","hk","indexB","jbfgm","ecojceoaejkkoed","cemodhmbcmgl","j","gdcnjj","kolaijoicbc","liibjjcini","lmbenj","eklingemgdjncaa","m","hkh","fblb","fk","nnfkfanaga","eldjml","iejn","gbmjfdooeeko","jafogijka","ngnfggojmhclkjd","bfagnfclg","imkeobcdidiifbm","ogeo","gicjog","cjnibenelm","ogoloc","edciifkaff","kbeeg","nebn","jdd","aeojhclmdn","dilbhl","dkk","bgmck","ohgkefkadonafg","labem","fheoglj","gkcanacfjfhogjc","eglkcddd","lelelihakeh","hhjijfiodfi","enehbibnhfjd","gkm","ggj","ag","hhhjogk","lllicdhihn","goakjjnk","lhbn","fhheedadamlnedh","bin","cl","ggjljjjf","fdcdaobhlhgj","nijlf","i","gaemagobjfc","dg","g","jhlelodgeekj","hcimohlni","fdoiohikhacgb","k","doiaigclm","bdfaoncbhfkdbjd","f","jaikbciac","cjgadmfoodmba","molokllh","gfkngeebnggo","lahd","n","ehfngoc","lejfcee","kofhmoh","cgda","de","kljnicikjeh","edomdbibhif","jehdkgmmofihdi","hifcjkloebel","gcghgbemjege","kobhhefbbb","aaikgaolhllhlm","akg","kmmikgkhnn","dnamfhaf","mjhj","ifadcgmgjaa","acnjehgkflgkd","bjj","maihjn","ojakklhl","ign","jhd","kndkhbebgh","amljjfeahcdlfdg","fnboolobch","gcclgcoaojc","kfokbbkllmcd","fec","dljma","noa","cfjie","fohhemkka","bfaldajf","nbk","kmbnjoalnhki","ccieabbnlhbjmj","nmacelialookal","hdlefnbmgklo","bfbblofk","doohocnadd","klmed","e","hkkcmbljlojkghm","jjiadlgf","ogadjhambjikce","bglghjndlk","gackokkbhj","oofohdogb","leiolllnjj","edekdnibja","gjhglilocif","ccfnfjalchc","gl","ihee","cfgccdmecem","mdmcdgjelhgk","laboglchdhbk","ajmiim","cebhalkngloae","hgohednmkahdi","ddiecjnkmgbbei","ajaengmcdlbk","kgg","ndchkjdn","heklaamafiomea","ehg","imelcifnhkae","hcgadilb","elndjcodnhcc","nkjd","gjnfkogkjeobo","eolega","lm","jddfkfbbbhia","cddmfeckheeo","bfnmaalmjdb","fbcg","ko","mojfj","kk","bbljjnnikdhg","l","calbc","mkekn","ejlhdk","hkebdiebecf","emhelbbda","mlba","ckjmih","odfacclfl","lgfjjbgookmnoe","begnkogf","gakojeblk","bfflcmdko","cfdclljcg","ho","fo","acmi","oemknmffgcio","mlkhk","kfhkndmdojhidg","ckfcibmnikn","dgoecamdliaeeoa","ocealkbbec","kbmmihb","ncikad","hi","nccjbnldneijc","hgiccigeehmdl","dlfmjhmioa","kmff","gfhkd","okiamg","ekdbamm","fc","neg","cfmo","ccgahikbbl","khhoc","elbg","cbghbacjbfm","jkagbmfgemjfg","ijceidhhajmja","imibemhdg","ja","idkfd","ndogdkjjkf","fhic","ooajkki","fdnjhh","ba","jdlnidngkfffbmi","jddjfnnjoidcnm","kghljjikbacd","idllbbn","d","mgkajbnjedeiee","fbllleanknmoomb","lom","kofjmmjm","mcdlbglonin","gcnboanh","fggii","fdkbmic","bbiln","cdjcjhonjgiagkb","kooenbeoongcle","cecnlfbaanckdkj","fejlmog","fanekdneoaammb","maojbcegdamn","bcmanmjdeabdo","amloj","adgoej","jh","fhf","cogdljlgek","o","joeiajlioggj","oncal","lbgg","elainnbffk","hbdi","femcanllndoh","ke","hmib","nagfahhljh","ibifdlfeechcbal","knec","oegfcghlgalcnno","abiefmjldmln","mlfglgni","jkofhjeb","ifjbneblfldjel","nahhcimkjhjgb","cdgkbn","nnklfbeecgedie","gmllmjbodhgllc","hogollongjo","fmoinacebll","fkngbganmh","jgdblmhlmfij","fkkdjknahamcfb","aieakdokibj","hddlcdiailhd","iajhmg","jenocgo","embdib","dghbmljjogka","bahcggjgmlf","fb","jldkcfom","mfi","kdkke","odhbl","jin","kcjmkggcmnami","kofig","bid","ohnohi","fcbojdgoaoa","dj","ifkbmbod","dhdedohlghk","nmkeakohicfdjf","ahbifnnoaldgbj","egldeibiinoac","iehfhjjjmil","bmeimi","ombngooicknel","lfdkngobmik","ifjcjkfnmgjcnmi","fmf","aoeaa","an","ffgddcjblehhggo","hijfdcchdilcl","hacbaamkhblnkk","najefebghcbkjfl","hcnnlogjfmmjcma","njgcogemlnohl","ihejh","ej","ofn","ggcklj","omah","hg","obk","giig","cklna","lihaiollfnem","ionlnlhjckf","cfdlijnmgjoebl","dloehimen","acggkacahfhkdne","iecd","gn","odgbnalk","ahfhcd","dghlag","bchfe","dldblmnbifnmlo","cffhbijal","dbddifnojfibha","mhh","cjjol","fed","bhcnf","ciiibbedklnnk","ikniooicmm","ejf","ammeennkcdgbjco","jmhmd","cek","bjbhcmda","kfjmhbf","chjmmnea","ifccifn","naedmco","iohchafbega","kjejfhbco","anlhhhhg"]
//		String s = "fohhemkkaecojceoaejkkoedkofhmohkcjmkggcmnami";
//		HashSet dict = new HashSet<String>();
//		String[] arr = {"kfomka","hecagbngambii","anobmnikj","c","nnkmfelneemfgcl","ah","bgomgohl","lcbjbg","ebjfoiddndih","hjknoamjbfhckb","eioldlijmmla","nbekmcnakif","fgahmihodolmhbi","gnjfe","hk","indexB","jbfgm","ecojceoaejkkoed","cemodhmbcmgl","j","gdcnjj","kolaijoicbc","liibjjcini","lmbenj","eklingemgdjncaa","m","hkh","fblb","fk","nnfkfanaga","eldjml","iejn","gbmjfdooeeko","jafogijka","ngnfggojmhclkjd","bfagnfclg","imkeobcdidiifbm","ogeo","gicjog","cjnibenelm","ogoloc","edciifkaff","kbeeg","nebn","jdd","aeojhclmdn","dilbhl","dkk","bgmck","ohgkefkadonafg","labem","fheoglj","gkcanacfjfhogjc","eglkcddd","lelelihakeh","hhjijfiodfi","enehbibnhfjd","gkm","ggj","ag","hhhjogk","lllicdhihn","goakjjnk","lhbn","fhheedadamlnedh","bin","cl","ggjljjjf","fdcdaobhlhgj","nijlf","i","gaemagobjfc","dg","g","jhlelodgeekj","hcimohlni","fdoiohikhacgb","k","doiaigclm","bdfaoncbhfkdbjd","f","jaikbciac","cjgadmfoodmba","molokllh","gfkngeebnggo","lahd","n","ehfngoc","lejfcee","kofhmoh","cgda","de","kljnicikjeh","edomdbibhif","jehdkgmmofihdi","hifcjkloebel","gcghgbemjege","kobhhefbbb","aaikgaolhllhlm","akg","kmmikgkhnn","dnamfhaf","mjhj","ifadcgmgjaa","acnjehgkflgkd","bjj","maihjn","ojakklhl","ign","jhd","kndkhbebgh","amljjfeahcdlfdg","fnboolobch","gcclgcoaojc","kfokbbkllmcd","fec","dljma","noa","cfjie","fohhemkka","bfaldajf","nbk","kmbnjoalnhki","ccieabbnlhbjmj","nmacelialookal","hdlefnbmgklo","bfbblofk","doohocnadd","klmed","e","hkkcmbljlojkghm","jjiadlgf","ogadjhambjikce","bglghjndlk","gackokkbhj","oofohdogb","leiolllnjj","edekdnibja","gjhglilocif","ccfnfjalchc","gl","ihee","cfgccdmecem","mdmcdgjelhgk","laboglchdhbk","ajmiim","cebhalkngloae","hgohednmkahdi","ddiecjnkmgbbei","ajaengmcdlbk","kgg","ndchkjdn","heklaamafiomea","ehg","imelcifnhkae","hcgadilb","elndjcodnhcc","nkjd","gjnfkogkjeobo","eolega","lm","jddfkfbbbhia","cddmfeckheeo","bfnmaalmjdb","fbcg","ko","mojfj","kk","bbljjnnikdhg","l","calbc","mkekn","ejlhdk","hkebdiebecf","emhelbbda","mlba","ckjmih","odfacclfl","lgfjjbgookmnoe","begnkogf","gakojeblk","bfflcmdko","cfdclljcg","ho","fo","acmi","oemknmffgcio","mlkhk","kfhkndmdojhidg","ckfcibmnikn","dgoecamdliaeeoa","ocealkbbec","kbmmihb","ncikad","hi","nccjbnldneijc","hgiccigeehmdl","dlfmjhmioa","kmff","gfhkd","okiamg","ekdbamm","fc","neg","cfmo","ccgahikbbl","khhoc","elbg","cbghbacjbfm","jkagbmfgemjfg","ijceidhhajmja","imibemhdg","ja","idkfd","ndogdkjjkf","fhic","ooajkki","fdnjhh","ba","jdlnidngkfffbmi","jddjfnnjoidcnm","kghljjikbacd","idllbbn","d","mgkajbnjedeiee","fbllleanknmoomb","lom","kofjmmjm","mcdlbglonin","gcnboanh","fggii","fdkbmic","bbiln","cdjcjhonjgiagkb","kooenbeoongcle","cecnlfbaanckdkj","fejlmog","fanekdneoaammb","maojbcegdamn","bcmanmjdeabdo","amloj","adgoej","jh","fhf","cogdljlgek","o","joeiajlioggj","oncal","lbgg","elainnbffk","hbdi","femcanllndoh","ke","hmib","nagfahhljh","ibifdlfeechcbal","knec","oegfcghlgalcnno","abiefmjldmln","mlfglgni","jkofhjeb","ifjbneblfldjel","nahhcimkjhjgb","cdgkbn","nnklfbeecgedie","gmllmjbodhgllc","hogollongjo","fmoinacebll","fkngbganmh","jgdblmhlmfij","fkkdjknahamcfb","aieakdokibj","hddlcdiailhd","iajhmg","jenocgo","embdib","dghbmljjogka","bahcggjgmlf","fb","jldkcfom","mfi","kdkke","odhbl","jin","kcjmkggcmnami","kofig","bid","ohnohi","fcbojdgoaoa","dj","ifkbmbod","dhdedohlghk","nmkeakohicfdjf","ahbifnnoaldgbj","egldeibiinoac","iehfhjjjmil","bmeimi","ombngooicknel","lfdkngobmik","ifjcjkfnmgjcnmi","fmf","aoeaa","an","ffgddcjblehhggo","hijfdcchdilcl","hacbaamkhblnkk","najefebghcbkjfl","hcnnlogjfmmjcma","njgcogemlnohl","ihejh","ej","ofn","ggcklj","omah","hg","obk","giig","cklna","lihaiollfnem","ionlnlhjckf","cfdlijnmgjoebl","dloehimen","acggkacahfhkdne","iecd","gn","odgbnalk","ahfhcd","dghlag","bchfe","dldblmnbifnmlo","cffhbijal","dbddifnojfibha","mhh","cjjol","fed","bhcnf","ciiibbedklnnk","ikniooicmm","ejf","ammeennkcdgbjco","jmhmd","cek","bjbhcmda","kfjmhbf","chjmmnea","ifccifn","naedmco","iohchafbega","kjejfhbco","anlhhhhg"};
//		for(int i=0;i<arr.length;i++){
//			dict.add(arr[i]);
//		}
//		System.out.println( wordBreak1(s,dict) );
		
		
		//test function findLongestString
//		String tmp = null;
//		int tmpLen = s.length()+1;
//		while( ( tmp =  findLongestString(dict , tmpLen ))!=null){
//			System.out.println("GOT:"+tmp);
//			tmpLen =tmp.length()+1;
//		}
		
	}
	
	
	public static boolean wordBreak1(String s, HashSet<String> dict) {
		if (dict.contains(s)) 
			return true;
		
		String str = s;
		Iterator<String> iterator=dict.iterator();		
		boolean gotone = false;
		String[] tmpArr=new String[dict.size()];
		//remove all not found
		int i=0;
		while(iterator.hasNext()){
			String tmpStr = iterator.next();
			if(str.indexOf(tmpStr)>=0){
				gotone = true;
				//break;
			}else{
				tmpArr[i]=tmpStr;
				i++;
			}
		}
		for(int j=0;j<i;j++){
			dict.remove(tmpArr[j]);
		}
// //FOR DEBUG
//iterator=dict.iterator();
//while(iterator.hasNext()){
//	String tmpStr = iterator.next();
//	System.out.print(tmpStr+",");
//}
//System.out.println("");
		
		if(!gotone){
			return false;
		}
		
		HashSet<String> dictR = new HashSet<String>();
		iterator=dict.iterator();
		String preStr = "";
		while( gotone!=false ){
			gotone=false;
			iterator=dict.iterator();
			String tmpStr = "";
			String bfRep = str;
System.out.println("BFR:"+bfRep);
			while(iterator.hasNext()){
				tmpStr = iterator.next();
//System.out.println("DICT:"+tmpStr);
				if(str.indexOf(tmpStr)>=0){
System.out.println("GOT:"+tmpStr);
					str=str.replaceFirst(tmpStr,"" );
					gotone = true;
					if(str.length()==0){
						return true;
					}
					preStr = tmpStr;
//System.out.println("GOT ONE:"+tmpStr+",TF:" +gotone);
				}
//System.out.println("AF-R:"+str);
			}
			
			if(str.length()!=0 && gotone == false){
				str=s;
				System.out.println("Removed:"+preStr);
				dict.remove(preStr);
				 
				dictR.add(preStr);
				//Check others 
				iterator=dict.iterator();
				gotone = false;
				while(iterator.hasNext()){
					if( str.indexOf( iterator.next() )>=0){
						gotone = true;
						break;
					}
				}
				if(!gotone){
					return false;
				}
				//if other 
			}
		}
		
		if(str.length()==0){
			return true;
		}
		return false;
	}
	
	
	public static boolean wordBreak(String s, HashSet<String> dict) {
		int len = s.length();
		boolean res = false;
		//String tmpStr = s;
		Iterator<String> iterator=dict.iterator();
		String[] str = new String[len*len];
		int[] idx = new int[len*len];
		int i=0;
		int tmpidx;
		String repStr = "";
		int nextStart = 0;
		//while(iterator.hasNext()){
		int tmpLen = s.length()+1;
		String tmp = null;
		while( ( tmp =  findLongestString(dict , tmpLen ))!=null){
			 //tmp = (String)iterator.next();
System.out.println("DICT:"+tmp );
			tmpLen = tmp.length()+1;
			boolean gotOne = false;
			while( ( tmpidx = s.indexOf( tmp,nextStart ) ) >=0 ){//BUG
				str[i]=tmp;
				idx[i]=tmpidx;
				i++;
				nextStart = tmpidx + tmp.length();
				gotOne = true;
				if(gotOne){
					break;
				}
//System.out.println("NextStart:"+nextStart);				
			}
			nextStart=0;
		}
		CollectionTool.printArray(str);
//		CollectionTool.printArray(idx);
		int act_len = i;
		//int nextIdx = 0;		
//System.out.println("Find next:"+findNext(nextIdx, str[2].length() ,idx));

		for(int k=0;k<act_len;k++){
			if(idx[k]==0){
System.out.println("STR:"+str[k]);
				int totalen = str[k].length();
				int nowIdx = 0;
				int nextIdx = -1;
				int strNowIdx = k;
				while( ( nextIdx = findNextIdx( nowIdx , str[strNowIdx].length() , idx) )>=0){
System.out.println("BF-totalen:"+totalen+",nowIdx:"+nowIdx+",|strNowIdx:"+strNowIdx+",nextIdx:"+nextIdx);
System.out.println("NOW-IDX:"+ nowIdx  + ",Next IDX:"+nextIdx );
System.out.println("STR NOW:"+ str[strNowIdx]+",STR NXT:"+ str[nextIdx] );
					if( totalen <= s.length()){ //+ str[nextIdx ].length()
						totalen += str[nextIdx].length();
						nowIdx +=  str[strNowIdx].length(); 
						//nextIdx = nextIdx+str[tmpIdx].length()-1;
						strNowIdx = nextIdx;
					}
System.out.println("AF-totalen:"+totalen+",nowIdx:"+nowIdx+",strNowIdx:"+strNowIdx+",nextIdx:"+nextIdx);
System.out.println("--------");
				}
				//totalIdx += str[nextIdx].length();
				if( totalen == s.length()){
					return true;
				}
			}
		}
        return res;
    }
	
	/**
	 * 
	 * @param nowidx
	 * @param idx
	 * @return
	 */
	public static int findNextIdx(int nowidx,int len,int[] idx){
		int res = -1;
		for(int k = 0;k<idx.length;k++){
			if( nowidx + len == idx[k] ){
				//res = k;
				return k;
			}
		}
		return res;
	}
	
	public static String findLongestString(  HashSet<String> dict ,int len){
System.out.println("FL-len:"+len);		
		Iterator<String> iterator = dict.iterator();
		String longestStr = "";
		int i=0;
		boolean getOne = false;
		while(iterator.hasNext()){
			String tmp = (String)iterator.next();
System.out.println("DICT:"+tmp );
			if(i==0){
				if(tmp.length() < len){
					getOne = true;
					longestStr = tmp;
					//dict.remove(tmp);
				}
			}else{
				if(tmp.length() < len && tmp.length() > longestStr.length()){
					getOne = true;
					longestStr = tmp;
					//dict.remove(tmp);
				}
			}
			i++;
		}
		if(getOne){
			dict.remove(longestStr);
			return longestStr;
		}else{
			return null;
		}
	}
	
	
}



//String tmpstr = "";
//int nextIdx = -1;
//int totalIdx = -1;
//boolean findone= false;
//for(int k = 0;k<idx.length;k++){
//	if(idx[k]==0){
//		findone = true;
//		tmpstr=str[k];
//		nextIdx = str[k].length();
//	}
//}
//if(!findone){
//	return false;
//}




//StringBuffer sb = new StringBuffer();
//for(int itr = 0;itr< tmp.length();itr++){
//	sb.append('&');
//}
//repStr =  sb.toString();
//System.out.println("REPS:"+repStr);				
//tmpStr.replaceFirst(tmp, repStr);
//System.out.println("AF REP:"+tmpStr);
/*
if( tmpidx == 0){//first word
	//tmpStr = tmpStr.substring(tmp.length());
	
	
}else if( tmpidx<0 && tmpidx < tmpStr.length()-tmp.length()  ){//in the middle
	tmpStr = tmpStr.substring(0,tmpidx) +  tmpStr.substring(tmpidx+tmp.length()-1);
}else if( tmpidx == tmpStr.length()-tmp.length() ){//in the last
	tmpStr = tmpStr.substring(tmp.length()-1);
}*/






/*
int minIdx1=-1,minIdx2=-1;
int k=0;
while(k==s.length()){
	minIdx1 = CollectionTool.findMin(idx,-1);
	minIdx2 = CollectionTool.findMin(idx,idx[minIdx1]);
	if( minIdx2 == minIdx1+str[minIdx1].length() ){
		
		if( str[minIdx1].length()+str[minIdx2].length() <s.length() ){
			tmpstr  = tmpstr+str[minIdx1]+str[minIdx2];
		}
		if(tmpstr.equals(s)){
			return true;
		}else if
	}
}
//tmp2 = CollectionTool.findMin(idx);

for(int j=0;j<i-1;j++){
	for(int k=j+1;k<i;k++){
		
		if(idx[j]<idx[k] && idx[j]+str[j].length()<=idx[k] ){
			if(s.equals(str[j]+str[k])){
				return true;
			}
		}else if( idx[k]<idx[j] && idx[k]+str[k].length()<=idx[j] ){
			if( s.equals(str[k]+str[j])){
				return true;
			}
		}else{
			res = false;
		}
	}
}*/
