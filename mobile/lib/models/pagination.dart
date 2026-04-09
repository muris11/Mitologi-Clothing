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
      currentPage: parseInt(json['currentPage'] ?? json['current_page'], 1),
      lastPage: parseInt(json['lastPage'] ?? json['last_page'], 1),
      total: parseInt(json['total'], 0),
      perPage: parseInt(json['perPage'] ?? json['per_page'], 12),
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

    // Standardized format: { data: [...], meta: { ... } }
    if (json['data'] is List) {
      productsList = json['data'] as List<dynamic>;
      final meta = json['meta'] ?? json['pagination'];
      if (meta is Map) {
        paging = Pagination.fromJson(Map<String, dynamic>.from(meta));
      }
    } else if (json['data'] is Map) {
      // Nested data format: { data: { products: [...], pagination: { ... } } }
      final data = Map<String, dynamic>.from(json['data'] as Map);
      if (data['products'] is List) {
        productsList = data['products'] as List<dynamic>;
        final meta = data['pagination'] ?? data['meta'];
        if (meta is Map) {
          paging = Pagination.fromJson(Map<String, dynamic>.from(meta));
        }
      } else if (data['items'] is List) {
        productsList = data['items'] as List<dynamic>;
        final meta = data['pagination'] ?? data['meta'];
        if (meta is Map) {
          paging = Pagination.fromJson(Map<String, dynamic>.from(meta));
        }
      }
    } else if (json['products'] is List) {
      // Legacy format: { products: [...], pagination: { ... } }
      productsList = json['products'] as List<dynamic>;
      if (json['pagination'] is Map) {
        paging = Pagination.fromJson(
          Map<String, dynamic>.from(json['pagination']),
        );
      }
    }

    return ProductListResponse(productsData: productsList, pagination: paging);
  }
}
