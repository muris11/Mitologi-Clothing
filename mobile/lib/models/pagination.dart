class Pagination {
  final int currentPage;
  final int lastPage;
  final int total;
  final int perPage;

  Pagination({
    required this.currentPage,
    required this.lastPage,
    required this.total,
    required this.perPage,
  });

  factory Pagination.fromJson(Map<String, dynamic> json) {
    int parseInt(dynamic value, int fallback) {
      if (value is int) return value;
      return int.tryParse(value?.toString() ?? '') ?? fallback;
    }

    return Pagination(
      currentPage: parseInt(json['current_page'] ?? json['currentPage'], 1),
      lastPage: parseInt(json['last_page'] ?? json['lastPage'], 1),
      total: parseInt(json['total'], 0),
      perPage: parseInt(json['per_page'] ?? json['perPage'], 12),
    );
  }

  bool get hasNextPage => currentPage < lastPage;
}

class ProductListResponse {
  final List<dynamic> productsData;
  final Pagination pagination;

  ProductListResponse({required this.productsData, required this.pagination});

  factory ProductListResponse.fromJson(Map<String, dynamic> json) {
    List<dynamic> productsList = [];
    Pagination paging = Pagination(
      currentPage: 1,
      lastPage: 1,
      total: 0,
      perPage: 12,
    );

    // New standardized format: { data: { products: [...], pagination: {...} } }
    if (json['data'] is Map) {
      final data = Map<String, dynamic>.from(json['data'] as Map);
      if (data.containsKey('products') && data['products'] is List) {
        productsList = data['products'] as List<dynamic>;
        if (data['pagination'] is Map) {
          paging = Pagination.fromJson(
            Map<String, dynamic>.from(data['pagination'] as Map),
          );
        }
      }
    } else if (json['data'] is List) {
      // Direct array format: { data: [...] }
      productsList = json['data'] as List<dynamic>;
    } else if (json['products'] is List) {
      // Old format: { products: [...], pagination: {...} }
      productsList = json['products'] as List<dynamic>;
      if (json['pagination'] is Map) {
        paging = Pagination.fromJson(
          Map<String, dynamic>.from(json['pagination'] as Map),
        );
      }
    }

    return ProductListResponse(productsData: productsList, pagination: paging);
  }
}
