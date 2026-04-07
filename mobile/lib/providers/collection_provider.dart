import 'package:flutter/material.dart';
import '../models/collection.dart';
import '../models/product.dart';
import '../services/collection_service.dart';

class CollectionProvider extends ChangeNotifier {
  final CollectionService _service = CollectionService();

  List<Collection> _collections = [];
  bool _isLoadingCollections = false;
  String? _collectionsError;

  Collection? _currentCollection;
  List<Product> _currentCollectionProducts = [];
  bool _isLoadingCollectionDetails = false;
  String? _collectionDetailsError;

  List<Collection> get collections => _collections;
  bool get isLoadingCollections => _isLoadingCollections;
  String? get collectionsError => _collectionsError;

  Collection? get currentCollection => _currentCollection;
  List<Product> get currentCollectionProducts => _currentCollectionProducts;
  bool get isLoadingCollectionDetails => _isLoadingCollectionDetails;
  String? get collectionDetailsError => _collectionDetailsError;

  Future<void> fetchCollections() async {
    _isLoadingCollections = true;
    _collectionsError = null;
    notifyListeners();

    try {
      _collections = await _service.getCollections();
    } catch (e) {
      _collectionsError = e.toString();
    } finally {
      _isLoadingCollections = false;
      notifyListeners();
    }
  }

  Future<void> fetchCollectionDetails(
    String handle, {
    String sortKey = 'RELEVANCE',
    bool reverse = false,
  }) async {
    _isLoadingCollectionDetails = true;
    _collectionDetailsError = null;
    notifyListeners();

    try {
      _currentCollection = await _service.getCollection(handle);
      _currentCollectionProducts = await _service.getCollectionProducts(
        handle,
        sortKey: sortKey,
        reverse: reverse,
      );
    } catch (e) {
      _collectionDetailsError = e.toString();
    } finally {
      _isLoadingCollectionDetails = false;
      notifyListeners();
    }
  }

  void clearCurrentCollection() {
    _currentCollection = null;
    _currentCollectionProducts = [];
    _collectionDetailsError = null;
    notifyListeners();
  }
}
