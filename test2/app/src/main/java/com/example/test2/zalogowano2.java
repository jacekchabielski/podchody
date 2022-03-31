package com.example.test2;

import androidx.appcompat.app.AppCompatActivity;

import android.content.Intent;
import android.os.AsyncTask;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.ResultSet;
import java.sql.Statement;
import java.util.ArrayList;

public class zalogowano2 extends AppCompatActivity {

    TextView wyswietlonePytanie;
    Button buttoncos;
    int ileTestow = 1;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_zalogowano);




    /*
        LocationManager lm = (LocationManager) getSystemService(Context.LOCATION_SERVICE);
        if (ActivityCompat.checkSelfPermission(this, Manifest.permission.ACCESS_FINE_LOCATION) != PackageManager.PERMISSION_GRANTED && ActivityCompat.checkSelfPermission(this, Manifest.permission.ACCESS_COARSE_LOCATION) != PackageManager.PERMISSION_GRANTED) {

            return;
        }
        Location location = lm.getLastKnownLocation(LocationManager.GPS_PROVIDER);
        double longitude = location.getLongitude();
        double latitude = location.getLatitude();

     */
        buttoncos = (Button) findViewById(R.id.Buttoncs);

        wyswietlonePytanie = (TextView) findViewById(R.id.wyswietlPytanie);

        //Log.e("long",String.valueOf(longitude));

        new zalogowano2.Task().execute();
    }


    String cojest = "";
    class Task extends AsyncTask<Void, Void, Void> {
        String error="";
        ArrayList podpowiedzi = new ArrayList();
        ArrayList idTestu = new ArrayList();

        //Intent intent = getIntent();
        //String str = intent.getStringExtra("idGry");


        @Override
        protected Void doInBackground(Void... voids){

            try{
                Class.forName("com.mysql.jdbc.Driver");
                Connection connection = DriverManager.getConnection("jdbc:mysql://remotemysql.com:3306/7MnHOOBoKy","7MnHOOBoKy", "YUcXDbJjrs");
                Connection connection2 = DriverManager.getConnection("jdbc:mysql://remotemysql.com:3306/7MnHOOBoKy","7MnHOOBoKy", "YUcXDbJjrs");
                Statement statement = connection.createStatement();
                ResultSet resultSet = statement.executeQuery("SELECT * FROM Testy WHERE idGry ="+15);

                Statement statement2 = connection2.createStatement();



                while(resultSet.next()){
                    //records += resultSet.getString(2)+"\n";
                    idTestu.add(resultSet.getString(1));
                }
                //int ktoreidTestu = 0;
                cojest = idTestu.get(ileTestow).toString();
                //Log.e("id z testow",cojest);

                ResultSet resultSet2 = statement2.executeQuery("SELECT * FROM podpowiedzi WHERE idTesty ="+49);
                while(resultSet2.next()){
                    //records += resultSet.getString(2)+"\n";
                    podpowiedzi.add(resultSet2.getString(2));
                    //Log.e("z tych dziwnych: ",podpowiedzi.get(0).toString());
                    Log.e("podpowiedzi wyswietl: ",resultSet2.getString(2));
                }
                //ktoreidTestu++;



            }
            catch(Exception e){
                error =  e.toString();
            }
            return null;
        }




        @Override
        protected void onPostExecute(Void aVoid){

            //ileTestow +=1;
            wyswietlonePytanie.setText(podpowiedzi.get(0).toString());


            if(error !=""){
                wyswietlonePytanie.setText("error");

            }
            super.onPostExecute(aVoid);
        }
    }


    public void przeslij(View v){

        String PrzeslijIdTest = cojest;
        Log.e("zawartosc w przeslij",cojest);
        Intent intent = new Intent(zalogowano2.this,rozwPytania.class);
        intent.putExtra("kluczId",PrzeslijIdTest);
        startActivity(intent);
    }
}