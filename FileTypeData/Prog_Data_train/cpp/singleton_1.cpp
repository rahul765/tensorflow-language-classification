//Singleton design pattern code structure.

#include <iostream>
using namespace std;  //this is a bad idea.

//Only one Batman is allowed in your superhero game :)
class Batman {

  private:
    //disallow constructor (and destructor) to prevent instance creation
    Batman() {};
    ~Batman() {};
    //Disallow copy contructor. No one else can be Batman
    Batman(const Batman& someOtherHero);
    //Disallow assignment operator.
    const Batman& operator=(const Batman& oldBat);

    string bruceWayne;

  public:
    string getBatman() {
      return bruceWayne;
    }
    void setHero(const string& hero) {
      bruceWayne = hero;
    }

    //Function accessible from anywhere. To get the single instance call
    // Batman::getInstance().getBatman(). Need to work on naming choice.
    static Batman& getInstance() {
      static Batman* instance = new Batman;
      return *instance;
    }
};

int main() {
  string hero = Batman::getInstance().getBatman();
  cout <<hero<<endl;
}