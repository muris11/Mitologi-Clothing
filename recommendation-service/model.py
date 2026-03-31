import pandas as pd
import numpy as np
import pickle
import os
from sklearn.naive_bayes import MultinomialNB
from sklearn.feature_extraction.text import CountVectorizer, TfidfVectorizer
from sklearn.preprocessing import LabelEncoder, OneHotEncoder
from sklearn.metrics.pairwise import cosine_similarity

MODEL_PATH = "recommender_model.pkl"

class RecommenderSystem:
    def __init__(self):
        self.nb_model = MultinomialNB()
        self.user_encoder = LabelEncoder()
        self.product_encoder = LabelEncoder()
        self.one_hot = OneHotEncoder(handle_unknown='ignore', sparse_output=False)
        
        self.tfidf = TfidfVectorizer(stop_words='english')
        self.content_matrix = None
        self.product_ids = []
        
        # Cold-start fallback: track popular products by interaction count
        self.product_interaction_counts = {}
        
        self.is_trained = False

    def train(self, interactions, products):
        """
        Train both Collaborative (Naive Bayes) and Content-Based models.
        interactions: list of dicts with 'user_id', 'product_id', 'action'
        products: list of dicts with 'id', 'title', 'description', 'category'
        """
        # 1. Train Naive Bayes (Collaborative / User-Product)
        if interactions:
            df = pd.DataFrame(interactions)
            
            # Track popular products by interaction count (cold-start fallback)
            self.product_interaction_counts = df['product_id'].value_counts().to_dict()
            
            self.user_encoder.fit(df['user_id'])
            self.product_encoder.fit(df['product_id'])
            
            user_indices = self.user_encoder.transform(df['user_id'])
            product_indices = self.product_encoder.transform(df['product_id'])
            
            X = self.one_hot.fit_transform(user_indices.reshape(-1, 1))
            y = product_indices
            
            self.nb_model.fit(X, y)
        
        # 2. Train Content-Based (Product-Product)
        if products:
            df_products = pd.DataFrame(products)
            self.product_ids = df_products['id'].tolist()
            
            # Combine features for TF-IDF
            df_products['content'] = df_products['title'] + " " + df_products['description'] + " " + df_products['category']
            
            self.content_matrix = self.tfidf.fit_transform(df_products['content'])
            
        self.is_trained = True
        self.save_model()

    def predict_user_products(self, user_id, limit=10):
        """
        Predict products for a user using Naive Bayes.
        """
        if not self.is_trained:
            return []
            
        try:
            if user_id not in self.user_encoder.classes_:
                return [] 
                
            user_idx = self.user_encoder.transform([user_id])
            X_new = self.one_hot.transform(user_idx.reshape(-1, 1))
            
            probs = self.nb_model.predict_proba(X_new)[0]
            
            # Get indices of top N products
            top_indices = probs.argsort()[-limit:][::-1]
            
            top_products = self.product_encoder.inverse_transform(top_indices)
            return top_products.tolist()
            
        except Exception as e:
            print(f"Prediction error: {e}")
            return []

    def predict_related_products(self, product_id, limit=5):
        """
        Find similar products using Content-Based Filtering (Cosine Similarity).
        """
        if self.content_matrix is None or product_id not in self.product_ids:
            return []
            
        try:
            idx = self.product_ids.index(product_id)
            
            # Compute similarity with all other products
            sim_scores = cosine_similarity(self.content_matrix[idx], self.content_matrix).flatten()
            
            # Get indices of top similar products (excluding itself)
            top_indices = sim_scores.argsort()[-(limit+1):-1][::-1]
            
            related_ids = [self.product_ids[i] for i in top_indices]
            return related_ids
            
        except Exception as e:
            print(f"Content prediction error: {e}")
            return []

    def get_popular_products(self, limit=10):
        """
        Cold-start fallback: return most popular products by interaction count.
        Used when a user has no interactions for collaborative filtering.
        """
        if not self.product_interaction_counts:
            return self.product_ids[:limit] if self.product_ids else []
        
        sorted_products = sorted(
            self.product_interaction_counts.items(),
            key=lambda x: x[1],
            reverse=True
        )
        return [pid for pid, _ in sorted_products[:limit]]

    def save_model(self):
        with open(MODEL_PATH, 'wb') as f:
            pickle.dump(self, f)

    @staticmethod
    def load_model():
        if os.path.exists(MODEL_PATH):
            with open(MODEL_PATH, 'rb') as f:
                return pickle.load(f)
        return RecommenderSystem()
