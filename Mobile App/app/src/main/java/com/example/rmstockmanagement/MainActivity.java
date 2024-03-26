package com.example.rmstockmanagement;

import androidx.appcompat.app.AppCompatActivity;

import android.os.Bundle;
import android.webkit.WebView;
import android.webkit.WebViewClient;

public class MainActivity extends AppCompatActivity {

    WebView rmStock;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        rmStock = findViewById(R.id.rmstock);
        rmStock.getSettings().setJavaScriptEnabled(true);
        rmStock.setWebViewClient(new WebViewClient());
        rmStock.loadUrl("https://dewkha.com/web/login.php");



    }

}