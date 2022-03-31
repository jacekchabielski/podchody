package com.example.test2;

import androidx.appcompat.app.AppCompatActivity;
import androidx.core.app.ActivityCompat;

import android.Manifest;
import android.content.Intent;
import android.content.SharedPreferences;
import android.content.pm.PackageManager;
import android.location.Criteria;
import android.location.Location;
import android.location.LocationListener;
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
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.Statement;
import java.util.ArrayList;

public class MainActivity extends AppCompatActivity {
    double lat;
    double lon;

    TextView text, errorText;
    Button show;
    EditText wpisanyKod, pseudonim;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        getLocation();

        text=(TextView) findViewById(R.id.textView);
        errorText=(TextView) findViewById(R.id.textView2);
        show=(Button) findViewById(R.id.button);
        wpisanyKod=(EditText) findViewById(R.id.duzePole);
        pseudonim = (EditText) findViewById(R.id.pseudonim);

        show.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                new Task().execute();
            }
        });

    }

    class Task extends AsyncTask<Void, Void, Void> {
        String records="", error="";
        String klucz = "";
        ArrayList kluczDostepu = new ArrayList();
        ArrayList idGry = new ArrayList();


        @Override
        protected Void doInBackground(Void... voids){
            try{
                Class.forName("com.mysql.jdbc.Driver");
                Connection connection = DriverManager.getConnection("jdbc:mysql://remotemysql.com:3306/7MnHOOBoKy","7MnHOOBoKy", "YUcXDbJjrs");
                Statement statement = connection.createStatement();
                ResultSet resultSet = statement.executeQuery("SELECT * FROM Gra");

                String pseudo = pseudonim.getText().toString();

                PreparedStatement stmt = connection.prepareStatement("INSERT into gracze(pseudonim, wspX, wspY) VALUES (?,?,?)");
                stmt.setString(1,pseudo);
                stmt.setDouble(2, lat);
                stmt.setDouble(3, lon);
                //stmt.setInt(4,idk);
                int count = stmt.executeUpdate();

                if (count == 1)
                {
                    Log.e("dodano ","dodano wsp gracza do bazy");
                }
                else
                {
                    Log.e("nie udal sie ","nie dodano wsp ");
                }

                //Connection connect_dla_gps = DriverManager.getConnection("jdbc:mysql://remotemysql.com:3306/7MnHOOBoKy","7MnHOOBoKy", "YUcXDbJjrs");
                //Statement state_dla_gps = connect_dla_gps.createStatement();
                //ResultSet rs_dla_gps = state_dla_gps.executeQuery("INSERT INTO gracze (wspX, wspY) VALUES"+"("+lat+","+lon+")");

                while(resultSet.next()){

                    //klucz += resultSet.getString(1)+"\n";
                    kluczDostepu.add(resultSet.getString(3));
                    idGry.add(resultSet.getString(1));

                }
                //int idk = Integer.parseInt(idGry.get(idGry.size()-1).toString());
                Log.e("lat",String.valueOf(lat));
                //rs_dla_gps.next();


            }
            catch(Exception e){
              error =  e.toString();
            }
            return null;
        }

        @Override
        protected void onPostExecute(Void aVoid){
            SharedPreferences preferences = getSharedPreferences("MySharedPref", 0);
            preferences.edit().remove("licznikTestow").commit();

            SharedPreferences prefer = getSharedPreferences("idGry", 0);
            prefer.edit().remove("idGry").commit();

            String wpisany_kod = wpisanyKod.getText().toString();

                for(int i=0;i< kluczDostepu.size();i++){

                    if(wpisany_kod.equals(kluczDostepu.get(i).toString())){
                        //text.setText(wpisany_kod);
                        String przeslijKlucz = idGry.get(i).toString();

                        SharedPreferences sharedPreferences = getSharedPreferences("idGryShared",MODE_PRIVATE);
                        SharedPreferences.Editor myEdit = sharedPreferences.edit();
                        myEdit.putInt("idGry", Integer.valueOf(przeslijKlucz));
                        myEdit.commit();

                        Intent intent = new Intent(MainActivity.this,zalogowano.class);

                        startActivity(intent);
                    }
                }
                //Toast.makeText(getApplicationContext(), "dupa", Toast.LENGTH_SHORT).show();


            if(error !=""){
                errorText.setText("error");
            }
            super.onPostExecute(aVoid);
        }
    }
    private void getLocation() {
        // Get the location manager
        LocationManager locationManager = (LocationManager)
                getSystemService(LOCATION_SERVICE);
        Criteria criteria = new Criteria();
        String bestProvider = locationManager.getBestProvider(criteria, false);
        if (ActivityCompat.checkSelfPermission(this, Manifest.permission.ACCESS_FINE_LOCATION) != PackageManager.PERMISSION_GRANTED && ActivityCompat.checkSelfPermission(this, Manifest.permission.ACCESS_COARSE_LOCATION) != PackageManager.PERMISSION_GRANTED) {

            return;
        }
        Location location = locationManager.getLastKnownLocation(bestProvider);
        LocationListener loc_listener = new LocationListener() {

            public void onLocationChanged(Location l) {}

            public void onProviderEnabled(String p) {}

            public void onProviderDisabled(String p) {}

            public void onStatusChanged(String p, int status, Bundle extras) {}
        };
        locationManager
                .requestLocationUpdates(bestProvider, 0, 0, loc_listener);
        location = locationManager.getLastKnownLocation(bestProvider);
        try {
            lat = location.getLatitude();
            lon = location.getLongitude();
        } catch (NullPointerException e) {
            lat = -1.0;
            lon = -1.0;
        }

    }
}