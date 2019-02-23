package com.android.popularmovies.model;

import org.json.JSONException;
import org.json.JSONObject;

public class MovieReview {
    private String author;
    private String review;

    public MovieReview(String author, String review) {
        this.author = author;
        this.review = review;
    }

    public MovieReview(JSONObject object) throws JSONException {
        this.author = object.getString("author");
        this.review = object.getString("content");
    }

    public String getAuthor() {
        return author;
    }

    public void setAuthor(String author) {
        this.author = author;
    }

    public String getReview() {
        return review;
    }

    public void setReview(String review) {
        this.review = review;
    }
}