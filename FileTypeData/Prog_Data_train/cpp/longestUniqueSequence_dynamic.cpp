#include <iostream>
#include <conio.h>

using namespace std;

int longestUniqueSubsttr(char s[])
{
    int n = strlen(s);
    int max_length = 1;
    int current_length = 1;
    int visit[256] = {-1};
    
    visit[s[0]] = 0;
    for(int i=1;i<n;i++)
    {
            if(visit[s[i]] == -1 || i-current_length > visit[s[i]])
            current_length++;
            else
            {
                if(current_length > max_length)
                max_length = current_length;
                
            }
            visit[s[i]] = i;
            
            }
            return max_length;
};



/* Driver program to test above function */
int main()
{
    char str[] = "ABDEFGABEF";
    printf("The input string is %s \n", str);
    int len =  longestUniqueSubsttr(str);
    printf("The length of the longest non-repeating character substring is %d", len);
 
    getchar();
    return 0;
}
