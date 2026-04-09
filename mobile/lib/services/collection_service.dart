import '../models/collection.dart';
import '../models/product.dart';
import 'api_service.dart';

class CollectionService {
  CollectionService({ApiService? api}) : _api = api ?? ApiService();

  final ApiService _api;

  Future<List<Collection>> getCollections() async {
    final response = await _api.get('/collections');
    if (response is List) {
      return response
          .map(
            (json) =>
                Collection.fromJson(Map<String, dynamic>.from(json as Map)),
          )
          .toList();
    } else if (response is Map<String, dynamic> && response['data'] is List) {
      return (response['data'] as List)
          .map(
            (json) =>
                Collection.fromJson(Map<String, dynamic>.from(json as Map)),
          )
          .toList();
    }
    return [];
  }

  Future<Collection> getCollection(String handle) async {
    final response = await _api.get('/collections/$handle');
    if (response is Map<String, dynamic> && response['data'] is Map) {
      return Collection.fromJson(
        Map<String, dynamic>.from(response['data'] as Map),
      );
    }
    return Collection.fromJson(Map<String, dynamic>.from(response as Map));
  }

  Future<List<Product>> getCollectionProducts(
    String handle, {
    String sortKey = 'RELEVANCE',
    bool reverse = false,
  }) async {
    final Map<String, dynamic> queryParams = {
      'sortKey': sortKey,
      'reverse': reverse.toString(),
    };
    final response = await _api.get(
      '/collections/$handle/products',
      queryParams: queryParams,
    );

    if (response is List) {
      return response
          .map(
            (json) => Product.fromJson(Map<String, dynamic>.from(json as Map)),
          )
          .toList();
    } else if (response is Map<String, dynamic> && response['data'] is List) {
      return (response['data'] as List)
          .map(
            (json) => Product.fromJson(Map<String, dynamic>.from(json as Map)),
          )
          .toList();
    }
    return [];
  }
}
