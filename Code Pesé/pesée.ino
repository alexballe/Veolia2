/* Lecture de la pesée
 * ------------------ 
 *
 * 
 *
 * Crée 13 février 2018
 * 
 * 
 *
 */

int AnalogPin = 0;      
int val = 0;       

class weighing{
  private:
     int threshold;
  public:
     weighing()
    {
      threshold=512;
    }
    int getThreshold()
    {
      return threshold;
    }
    bool filling_verif(int measured)
    {
      if(measured>=threshold)
      {
        return true;
          
      }else
      {
        return false;
        
      }
    }
};




void setup() {
 Serial.begin(9600);
 pinMode(4, OUTPUT);
}

weighing * trash =new weighing ;

void loop() {
  val = analogRead(AnalogPin);    
  
  if(trash->filling_verif(val))
  {
    digitalWrite(4, HIGH);
    Serial.println("la poubelle est  rempli");
  }else
  {
    digitalWrite(4, LOW);
    Serial.println("la poubelle n'est pas rempli");
  }
 
  delay(500);                  
}
 
