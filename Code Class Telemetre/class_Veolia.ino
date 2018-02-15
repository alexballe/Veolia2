#include "Veolia.h"
#include "SPI.h"
#include "Ethernet.h"
#include "sha1.h"
#include "mysql.h"

Veolia Pin(2,3);
int Led = 7;

byte mac_addr[] = { 0xDE, 0xAD, 0xBE, 0xEF, 0xFE, 0xED };
Connector my_conn;
char user[] = "root";
char password[] = "";
IPAddress server_addr(192, 168, 64, 104);

void setup() 
{
  Serial.begin (9600);    //Fixation du débit de communication en nb de caractères par seconde (baud) pour la communication série.
  //Ethernet.begin(mac_addr);
  //Serial.println("Connecting...");
  //if (my_conn.mysql_connect(server_addr, 80, user, password)) 
  //{
  //  Serial.println("Connection success.");
  //}
  //else
  //{
  //  Serial.println("Connection failed.");
  //}


//  Configuration des broches utilisées
//---------------------------------------------------------------------------------------
  pinMode(Led, OUTPUT);
  Pin.init();
//---------------------------------------------------------------------------------------
}

void loop() 
{  
  float Distance = Pin.mesureDistance(Pin.getTrigger(), Pin.getEcho());    //Recuperation de la Distance du Telemetre
  
  float EspaceLibre = (Distance/10)*100;
  float Pourcentage = 100.00 - EspaceLibre;
  
  if(Pourcentage >= 00.00)
  {
    Serial.print("Remplissage de la poubelle : ");
    Serial.print(Pourcentage);
    Serial.println(" %");
  }
  
  if(Pourcentage >= 50)
  {
    digitalWrite(Led,HIGH);       //On allume la LED si la Distance est superieur ou égale à 10
  }
  else 
  {
    digitalWrite(Led,LOW);        //On éteint la LED si la Distance est inferieur à 10
  }

  delay(2000);
}
