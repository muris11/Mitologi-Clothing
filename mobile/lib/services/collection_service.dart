import '../models/collection.dart';
import '../models/product.dart';
import 'api_service.dart';

class CollectionService {
  final ApiService _api = ApiService();

  Future<List<Collection>> getCollections() async {
    final response = await _api.get('/collections');
    if (response is List) {
      return response.map((json) => Collection.fromJson(json)).toList();
    } else if (response != null && response['data'] != null) {
      return (response['data'] as List)
          .map((json) => Collection.fromJson(json))
          .toList();
    }
    return [];
  }

  Future<Collection> getCollection(String handle) async {
    final response = await _api.get('/collections/$handle');
    if (response != null && response['data'] != null) {
      return Collection.fromJson(response['data']);
    }
    return Collection.fromJson(response);
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
      return response.map((json) => Product.fromJson(json)).toList();
    } else if (response != null && response['data'] != null) {
      return (response['data'] as List)
          .map((json) => Product.fromJson(json))
          .toList();
    }
    return [];
  }
}
