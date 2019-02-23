package pl.dmichalski.algorithms.nwd;

/**
 * Author: Daniel
 */
public class Nwd {

    /**
     * Metoda wyszukuje NWD
     * @param a pierwsza liczba
     * @param b druga liczba
     * @return zwraca najwiekszy wspolny dzielnik obu liczb
     */
    public int findNwd(int a, int b) {
        if(a < 0 || b < 0){
            throw new IllegalArgumentException("Argumenty muszą być dodatnie");
        }
        while (a != b) {
            if (a > b) {
                a = a - b;
            } else {
                b = b - a;
            }
        }
        return a;
    }
}
