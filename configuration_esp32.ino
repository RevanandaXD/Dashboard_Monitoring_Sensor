#include <WiFi.h>
#include <HTTPClient.h>
#include "DHT.h"

// --- WiFi ---
const char* ssid = "Alfayra";
const char* password = "Revananda2006";

// --- Server PHP ---
String serverName = "http://192..../monitoring_sensor/api_receive.php";

// --- Pin Sensor ---
#define LDR_PIN 34
#define DHTPIN 4
#define DHTTYPE DHT11

DHT dht(DHTPIN, DHTTYPE);

void setup() {
  Serial.begin(115200);
  dht.begin();

  WiFi.begin(ssid, password);
  Serial.print("Menghubungkan WiFi");
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("\nWiFi terhubung, IP: " + WiFi.localIP().toString());
}

void loop() {
  if (WiFi.status() == WL_CONNECTED) {
    // Baca sensor
    int ldrValue = analogRead(LDR_PIN);
    float temp = dht.readTemperature();
    float hum  = dht.readHumidity();

    // Validasi data DHT
    if (isnan(temp) || isnan(hum)) {
      Serial.println("Gagal membaca DHT11");
    } else {
      // Buat JSON
      String jsonData = "{";
      jsonData += "\"temperature\":" + String(temp, 1) + ",";
      jsonData += "\"humidity\":" + String(hum, 1) + ",";
      jsonData += "\"ldr\":" + String(ldrValue);
      jsonData += "}";

      Serial.println("Mengirim data: " + jsonData);

      // Kirim ke server
      HTTPClient http;
      http.begin(serverName);
      http.addHeader("Content-Type", "application/json");

      int httpResponseCode = http.POST(jsonData);
      if (httpResponseCode > 0) {
        String response = http.getString();
        Serial.println("Server response: " + response);
      } else {
        Serial.printf("Gagal kirim, code: %d\n", httpResponseCode);
      }
      http.end();
    }
  } else {
    Serial.println("WiFi terputus, mencoba ulang...");
    WiFi.reconnect();
  }

  delay(5000); // kirim setiap 5 detik
}
