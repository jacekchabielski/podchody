package com.example.test2;

import androidx.appcompat.app.AppCompatActivity;
import androidx.core.app.ActivityCompat;

import android.Manifest;
import android.annotation.SuppressLint;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.content.pm.PackageManager;
import android.location.Location;
import android.location.LocationManager;
import android.os.AsyncTask;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;
import android.widget.Toast;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.ResultSet;
import java.sql.Statement;
import java.util.ArrayList;

public class zalogowano extends AppCompatActivity {

    TextView wyswietlonePytanie;
    Button buttoncos;
    int ileTestow = 0;

    ArrayList wspX = new ArrayList();
    ArrayList wspY = new ArrayList();


    ArrayList GraczWspX = new ArrayList();
    ArrayList GraczWspY = new ArrayList();
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_zalogowano);





        buttoncos = (Button) findViewById(R.id.Buttoncos);

        wyswietlonePytanie = (TextView) findViewById(R.id.wyswietlonePytanie);

        //Log.e("long",String.valueOf(longitude));

        new zalogowano.Task().execute();
    }





    String cojest = "";
    class Task extends AsyncTask<Void, Void, Void> {
        String error="";
        ArrayList podpowiedzi = new ArrayList();
        ArrayList idTestu = new ArrayList();


        @SuppressLint("WrongConstant")
        SharedPreferences idGry = getSharedPreferences("idGryShared",MODE_APPEND);
        int idg = idGry.getInt("idGry", 0);

        String str = String.valueOf(idg);




        @Override
        protected Void doInBackground(Void... voids){

            try{
                Class.forName("com.mysql.jdbc.Driver");
                Connection connection = DriverManager.getConnection("jdbc:mysql://remotemysql.com:3306/7MnHOOBoKy","7MnHOOBoKy", "YUcXDbJjrs");
                Connection connection2 = DriverManager.getConnection("jdbc:mysql://remotemysql.com:3306/7MnHOOBoKy","7MnHOOBoKy", "YUcXDbJjrs");
                Connection connection3 = DriverManager.getConnection("jdbc:mysql://remotemysql.com:3306/7MnHOOBoKy","7MnHOOBoKy", "YUcXDbJjrs");
                Statement statement = connection.createStatement();
                ResultSet resultSet = statement.executeQuery("SELECT * FROM Testy WHERE idGry ="+str);

                Statement statement2 = connection2.createStatement();
                Statement statement3 = connection2.createStatement();

                @SuppressLint("WrongConstant")
                SharedPreferences sh = getSharedPreferences("MySharedPref",MODE_APPEND);
                int a = sh.getInt("licznikTestow", 0);
                ileTestow = a;

                while(resultSet.next()){

                    idTestu.add(resultSet.getString(1));
                    wspX.add(resultSet.getString(3));
                    wspY.add(resultSet.getString(4));

                }

                cojest = idTestu.get(ileTestow).toString();
                Log.e("id z testow",cojest);

                ResultSet resultSet2 = statement2.executeQuery("SELECT * FROM podpowiedzi WHERE idTesty ="+cojest);
                while(resultSet2.next()){
                    //records += resultSet.getString(2)+"\n";
                    podpowiedzi.add(resultSet2.getString(2));
                    Log.e("z tych dziwnych: ",podpowiedzi.get(0).toString());
                    Log.e("podpowiedzi wyswietl: ",resultSet2.getString(2));
                }

                ResultSet resultSet3 = statement3.executeQuery("SELECT * FROM gracze");
                while(resultSet3.next()){
                   GraczWspX.add(resultSet3.getString(3));
                   GraczWspY.add(resultSet3.getString(4));
                }


            }
            catch(Exception e){
                error =  e.toString();
            }
            return null;
        }



        @Override
        protected void onPostExecute(Void aVoid){


                        wyswietlonePytanie.setText(podpowiedzi.get(0).toString());

            if(error !=""){
                wyswietlonePytanie.setText("error");

            }
            super.onPostExecute(aVoid);
        }
    }


    public void przeslij(View v){
        Double wspolrzednaTestuX = Double.parseDouble(wspX.get(ileTestow).toString());
        Double wspolrzednaTestuY = Double.parseDouble(wspY.get(ileTestow).toString());

        Double wspolrzednaGraczaX = Double.parseDouble(GraczWspX.get(GraczWspX.size()-1).toString());
        Double wspolrzednaGraczaY = Double.parseDouble(GraczWspY.get(GraczWspY.size()-1).toString());
        Log.e("wsp testu X",wspolrzednaTestuX.toString());
        Log.e("wsp testu Y",wspolrzednaTestuY.toString());

        Log.e("wsp gracza X",wspolrzednaGraczaX.toString());
        Log.e("wsp gracza Y",wspolrzednaGraczaY.toString());
        Double roznicaX = wspolrzednaTestuX - wspolrzednaGraczaX;
        Double roznicaY = wspolrzednaTestuY - wspolrzednaGraczaY;

        Log.e("roznica X wynosi: ",roznicaX.toString());
        Log.e("roznica Y wynosi: ",roznicaY.toString());

        if(roznicaX > 1.0386 && roznicaY > 1.6){
            Toast.makeText(getApplicationContext(), "Szukaj dalej !!", Toast.LENGTH_SHORT).show();

        }else{
            String PrzeslijIdTest = cojest;
            Log.e("zawartosc w przeslij",cojest);
            Intent intent = new Intent(zalogowano.this,rozwPytania.class);
            intent.putExtra("kluczId",PrzeslijIdTest);
            startActivity(intent);
        }

    }
}