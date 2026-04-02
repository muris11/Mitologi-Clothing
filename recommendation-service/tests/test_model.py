"""
Test suite for recommendation-service.

Run with: python -m pytest tests/ -v
"""

import pytest
import json
import os
from unittest.mock import Mock, patch, MagicMock
import pandas as pd
import numpy as np
from model import RecommenderSystem


class TestRecommenderSystem:
    """Test cases for RecommenderSystem model."""

    @pytest.fixture
    def recommender(self):
        """Create fresh recommender instance for each test."""
        return RecommenderSystem()

    def test_initialization(self, recommender):
        """Test recommender initializes with correct defaults."""
        assert not recommender.is_trained
        assert recommender.content_matrix is None
        assert recommender.product_ids == []
        assert recommender.product_interaction_counts == {}

    def test_train_with_empty_data(self, recommender):
        """Test training with no data."""
        recommender.train([], [])
        assert recommender.is_trained
        # Should handle gracefully without errors

    def test_train_with_interactions(self, recommender):
        """Test collaborative filtering training."""
        interactions = [
            {'user_id': 1, 'product_id': 101},
            {'user_id': 1, 'product_id': 102},
            {'user_id': 2, 'product_id': 101},
            {'user_id': 2, 'product_id': 103},
        ]
        recommender.train(interactions, [])
        
        assert recommender.is_trained
        assert len(recommender.product_interaction_counts) > 0

    def test_train_with_products(self, recommender):
        """Test content-based training."""
        products = [
            {'id': 101, 'title': 'T-Shirt', 'description': 'Cotton shirt', 'category': 'Apparel'},
            {'id': 102, 'title': 'Jeans', 'description': 'Blue denim', 'category': 'Apparel'},
            {'id': 103, 'title': 'Jacket', 'description': 'Winter jacket', 'category': 'Outerwear'},
        ]
        recommender.train([], products)
        
        assert recommender.is_trained
        assert len(recommender.product_ids) == 3
        assert recommender.content_matrix is not None

    def test_predict_user_products_untrained(self, recommender):
        """Test prediction before training returns empty list."""
        result = recommender.predict_user_products(1, limit=5)
        assert result == []

    def test_predict_related_products_untrained(self, recommender):
        """Test content prediction before training returns empty list."""
        result = recommender.predict_related_products(101, limit=5)
        assert result == []

    def test_get_popular_products_untrained(self, recommender):
        """Test popular products before training returns empty list."""
        result = recommender.get_popular_products(limit=5)
        assert result == []

    def test_get_popular_products_trained(self, recommender):
        """Test popular products after training."""
        interactions = [
            {'user_id': 1, 'product_id': 101},
            {'user_id': 1, 'product_id': 101},  # 101 appears twice
            {'user_id': 2, 'product_id': 102},
        ]
        recommender.train(interactions, [])
        
        popular = recommender.get_popular_products(limit=2)
        assert len(popular) > 0
        assert 101 in popular  # Most popular should be first

    def test_train_with_null_values(self, recommender):
        """Test training handles null values gracefully."""
        interactions = [
            {'user_id': 1, 'product_id': 101},
            {'user_id': None, 'product_id': 102},  # Should be filtered
            {'user_id': 2, 'product_id': None},      # Should be filtered
        ]
        recommender.train(interactions, [])
        assert recommender.is_trained

    def test_predict_user_products_unknown_user(self, recommender):
        """Test prediction for unknown user."""
        interactions = [
            {'user_id': 1, 'product_id': 101},
        ]
        recommender.train(interactions, [])
        
        # User 999 doesn't exist
        result = recommender.predict_user_products(999, limit=5)
        assert result == []

    def test_predict_related_products_unknown_product(self, recommender):
        """Test prediction for unknown product."""
        products = [
            {'id': 101, 'title': 'T-Shirt', 'description': 'Cotton', 'category': 'Apparel'},
        ]
        recommender.train([], products)
        
        # Product 999 doesn't exist
        result = recommender.predict_related_products(999, limit=5)
        assert result == []

    def test_save_and_load_model(self, recommender, tmp_path):
        """Test model persistence."""
        # Setup
        interactions = [{'user_id': 1, 'product_id': 101}]
        products = [{'id': 101, 'title': 'Test', 'description': 'Test', 'category': 'Test'}]
        recommender.train(interactions, products)
        
        # Save
        original_path = RecommenderSystem._RecommenderSystem__dict__.get('MODEL_PATH', 'recommender_model.pkl')
        with patch('model.MODEL_PATH', str(tmp_path / 'test_model.pkl')):
            recommender.save_model()
            
            # Load
            loaded = RecommenderSystem.load_model()
            assert loaded.is_trained


class TestAPIRoutes:
    """Test cases for Flask API endpoints."""

    @pytest.fixture
    def client(self):
        """Create test client."""
        from app import app
        app.config['TESTING'] = True
        with app.test_client() as client:
            yield client

    def test_health_endpoint(self, client):
        """Test health check endpoint."""
        response = client.get('/api/health')
        assert response.status_code == 200
        
        data = json.loads(response.data)
        assert data['status'] == 'ok'
        assert 'trained' in data

    def test_recommend_user_without_api_key(self, client):
        """Test user recommendations without API key returns 401."""
        response = client.get('/api/recommendations/user/1')
        assert response.status_code == 401

    def test_recommend_product_without_api_key(self, client):
        """Test product recommendations without API key returns 401."""
        response = client.get('/api/recommendations/product/1')
        assert response.status_code == 401

    def test_train_without_api_key(self, client):
        """Test training endpoint without API key returns 401."""
        response = client.post('/api/train', json={})
        assert response.status_code == 401


class TestTrainJob:
    """Test cases for training job."""

    @patch('train_job.requests.get')
    def test_run_train_job_success(self, mock_get):
        """Test successful training job."""
        from train_job import run_train_job
        
        # Mock successful response
        mock_response = Mock()
        mock_response.status_code = 200
        mock_response.json.return_value = {
            'interactions': [
                {'user_id': 1, 'product_id': 101},
                {'user_id': 2, 'product_id': 102},
            ],
            'products': [
                {'id': 101, 'title': 'T-Shirt', 'description': 'Cotton', 'category': 'Apparel'},
            ]
        }
        mock_get.return_value = mock_response
        
        result = run_train_job()
        assert result is True
        mock_get.assert_called_once()

    @patch('train_job.requests.get')
    def test_run_train_job_api_failure(self, mock_get):
        """Test training job handles API failure."""
        from train_job import run_train_job
        
        # Mock failed response
        mock_response = Mock()
        mock_response.status_code = 500
        mock_get.return_value = mock_response
        
        result = run_train_job()
        assert result is False

    @patch('train_job.requests.get')
    def test_run_train_job_empty_data(self, mock_get):
        """Test training job handles empty data."""
        from train_job import run_train_job
        
        mock_response = Mock()
        mock_response.status_code = 200
        mock_response.json.return_value = {'interactions': [], 'products': []}
        mock_get.return_value = mock_response
        
        result = run_train_job()
        assert result is False

    @patch('train_job.requests.get')
    def test_run_train_job_network_error(self, mock_get):
        """Test training job handles network errors."""
        from train_job import run_train_job
        import requests
        
        mock_get.side_effect = requests.exceptions.RequestException("Connection failed")
        
        result = run_train_job()
        assert result is False


class TestSecurity:
    """Security test cases."""

    def test_api_key_validation(self):
        """Test API key validation logic."""
        from app import require_api_key, API_KEY
        
        # Mock function to wrap
        mock_func = Mock(return_value="success")
        wrapped = require_api_key(mock_func)
        
        # Test with valid key
        with patch('app.request') as mock_request:
            mock_request.headers.get.return_value = API_KEY
            result = wrapped()
            assert result == "success"
            mock_func.assert_called_once()

    def test_api_key_validation_invalid(self):
        """Test API key rejects invalid keys."""
        from app import require_api_key
        
        mock_func = Mock()
        wrapped = require_api_key(mock_func)
        
        with patch('app.request') as mock_request:
            mock_request.headers.get.return_value = "invalid-key"
            result = wrapped()
            
            assert result[1] == 401  # Status code
            mock_func.assert_not_called()


class TestErrorHandling:
    """Error handling test cases."""

    def test_parse_limit_valid(self):
        """Test limit parsing with valid values."""
        from app import parse_limit
        
        with patch('app.request') as mock_request:
            mock_request.args.get.return_value = "10"
            limit, error, status = parse_limit(10)
            
            assert error is None
            assert limit == 10

    def test_parse_limit_invalid(self):
        """Test limit parsing with invalid values."""
        from app import parse_limit
        
        with patch('app.request') as mock_request:
            mock_request.args.get.return_value = "invalid"
            limit, error, status = parse_limit(10)
            
            assert error is not None
            assert status == 400

    def test_parse_limit_out_of_range(self):
        """Test limit parsing with out of range values."""
        from app import parse_limit
        
        with patch('app.request') as mock_request:
            mock_request.args.get.return_value = "999"
            limit, error, status = parse_limit(10, max_value=100)
            
            assert error is not None
            assert status == 400


if __name__ == "__main__":
    pytest.main([__file__, "-v"])
