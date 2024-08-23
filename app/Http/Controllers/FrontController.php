<?php

namespace App\Http\Controllers;

use App\Models\ArticleNews;
use App\Models\Author;
use App\Models\BannerAdvertisement;
use App\Models\Category;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    //
    public function index() {
        $categories = Category::all();

        $articles = ArticleNews::with(['category'])
        ->where('is_featured', 'not_featured')
        ->latest()
        ->take(3)
        ->get();

        $featured_articles = ArticleNews::with(['category'])
        ->where('is_featured', 'featured')
        ->inRandomOrder()
        ->take(3)
        ->get();

        $bannerAds = BannerAdvertisement::where('is_active', 'active')
        ->where('type', 'banner')
        ->inRandomOrder()
        // ->take(1)
        ->first();

        $authors = Author::all();

        $showRandomArticle = ArticleNews::where('is_featured', 'not_featured') // Misalnya menampilkan hanya artikel yang di-feature
        ->inRandomOrder()
        ->take(6)
        ->get();
        
        $showFeaturedArticle = ArticleNews::where('is_featured', 'featured') // Misalnya menampilkan hanya artikel yang di-feature
        ->inRandomOrder()
        ->first();
        // ->take(1)
        // ->get();

        return view('frontend.index', compact('categories', 'articles', 'authors',
         'featured_articles', 'bannerAds', 'showRandomArticle', 'showFeaturedArticle'));
    }

    public function category(Category $category) {
        $categories = Category::all();
        $bannerAds = BannerAdvertisement::where('is_active', 'active')
        ->where('type', 'banner')
        ->inRandomOrder()
        // ->take(1)
        ->first();
        return view('frontend.category', compact('category', 'categories' , 'bannerAds'));
    }

    public function author(Author $author) {
        $categories = Category::all();
        $bannerAds = BannerAdvertisement::where('is_active', 'active')
        ->where('type', 'banner')
        ->inRandomOrder()
        // ->take(1)
        ->first();
        return view('frontend.author', compact('author', 'categories', 'bannerAds'));
    }

    public function search(Request $request) {
        $categories = Category::all();
    
        $request->validate([
            'keyword' => ['required', 'string', 'max:255'],
        ]);
    
        $keyword = $request->keyword;
    
        $articles = ArticleNews::with(['category', 'author'])
            ->where('name', 'like', '%' . $keyword . '%')->paginate(6);
    
        return view('frontend.search', compact('articles', 'categories', 'keyword'));
    } 

    public function details(ArticleNews $articleNews) {
        $categories = Category::all();

        $bannerAds = BannerAdvertisement::where('is_active', 'active')
        ->where('type', 'banner')
        ->inRandomOrder()
        // ->take(1)
        ->first();

        $articles = ArticleNews::with(['category'])
        ->where('is_featured', 'not_featured')
        ->where('id', '!=', $articleNews->id)
        ->latest()
        ->take(3)
        ->get();

        $square_ads = BannerAdvertisement::where('type', 'square')
        ->where('is_active', 'active')       
        ->inRandomOrder()
        ->take(2)
        ->get();

        if($square_ads->count() <2 ) {
            $square_ads_1 = $square_ads->first();
            $square_ads_2 = $square_ads->first();
        }else {
            $square_ads_1 = $square_ads->get(0);
            $square_ads_2 = $square_ads->get(1);
        }

        $author_news = ArticleNews::where('author_id', $articleNews->author_id)
        ->where('id', '!=', $articleNews->id)
        ->inRandomOrder()
        ->get();

        return view('frontend.details', compact('articleNews', 'categories', 'bannerAds', 'articles' , 
        'square_ads_1', 'square_ads_2', 'author_news'));
    }
}