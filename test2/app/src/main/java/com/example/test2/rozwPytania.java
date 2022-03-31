package com.example.test2;

import androidx.appcompat.app.AppCompatActivity;

import android.annotation.SuppressLint;
import android.content.Intent;
import android.content.SharedPreferences;
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

public class rozwPytania extends AppCompatActivity {

    TextView widok;
    EditText wpisywanie;
    Button czyDobre ;

    int zrobionyTest;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_rozw_pytania);

        widok = (TextView) findViewById(R.id.wyswietlzp);
        wpisywanie = (EditText) findViewById(R.id.wpiszOdp);
        czyDobre = (Button) findViewById(R.id.sprawdz);

        new rozwPytania.Task().execute();

        czyDobre.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                new rozwPytania.Task().execute();
            }
        });


    }
    class Task extends AsyncTask<Void, Void, Void> {
        String records = "", error = "";
        String klucz = "";
        ArrayList pytania = new ArrayList();
        ArrayList odpowiedzi = new ArrayList();

        Intent intent = getIntent();
        String str = intent.getStringExtra("kluczId");


        @Override
        protected Void doInBackground(Void... voids) {
            try {
                Class.forName("com.mysql.jdbc.Driver");
                Connection connection = DriverManager.getConnection("jdbc:mysql://remotemysql.com:3306/7MnHOOBoKy", "7MnHOOBoKy", "YUcXDbJjrs");
                Statement statement = connection.createStatement();
                ResultSet resultSet = statement.executeQuery("SELECT * FROM pytania WHERE idTestu=" + str);

                while (resultSet.next()) {

                    pytania.add(resultSet.getString(2));
                    odpowiedzi.add(resultSet.getString(3));

                    Log.e("pytanie: ", resultSet.getString(2));
                }
            } catch (Exception e) {
                error = e.toString();
            }
            return null;
        }

        @Override
        protected void onPostExecute(Void aVoid) {

            int i = 0;
            widok.setText(pytania.get(i).toString());

            String zawartosc = wpisywanie.getText().toString();

            if (zawartosc.equals(odpowiedzi.get(i).toString())) {
                Toast.makeText(getApplicationContext(), "dobrze !", Toast.LENGTH_SHORT).show();

                wpisywanie.getText().clear();
                i++;


                Intent intent = new Intent(rozwPytania.this, zalogowano.class);
                Toast.makeText(getApplicationContext(), "zaliczyles test !", Toast.LENGTH_SHORT).show();
                startActivity(intent);



                @SuppressLint("WrongConstant") SharedPreferences sh = getSharedPreferences("MySharedPref", MODE_APPEND);
                int aktualnyLicznik = sh.getInt("licznikTestow", 0);
                zrobionyTest = aktualnyLicznik;

                zrobionyTest++;
                Log.e("ile zrobionych testow", String.valueOf(zrobionyTest));
                Log.e("ile jest pytan", String.valueOf(pytania.size()));
                //if(zrobionyTest < pytania.size()){
                 //   Intent in = new Intent(rozwPytania.this, Podsumowanie.class);
                  //  Toast.makeText(getApplicationContext(), "", Toast.LENGTH_SHORT).show();
                   // startActivity(in);
                //}

                SharedPreferences sharedPreferences = getSharedPreferences("MySharedPref", MODE_PRIVATE);
                SharedPreferences.Editor myEdit = sharedPreferences.edit();
                myEdit.putInt("licznikTestow", zrobionyTest);
                myEdit.commit();




            if (error != "") {
                widok.setText("error");
            }
            super.onPostExecute(aVoid);
        }


}}}
