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
    return Pagination(
      currentPage: json['current_page'] is int
          ? json['current_page']
          : int.tryParse(json['current_page']?.toString() ?? '1') ?? 1,
      lastPage: json['last_page'] is int
          ? json['last_page']
          : int.tryParse(json['last_page']?.toString() ?? '1') ?? 1,
      total: json['total'] is int
          ? json['total']
          : int.tryParse(json['total']?.toString() ?? '0') ?? 0,
      perPage: json['per_page'] is int
          ? json['per_page']
          : int.tryParse(json['per_page']?.toString() ?? '12') ?? 12,
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
    if (json.containsKey('data') && json['data'] is Map<String, dynamic>) {
      final data = json['data'];
      if (data.containsKey('products') && data['products'] is List) {
        productsList = data['products'];
        if (data['pagination'] is Map<String, dynamic>) {
          paging = Pagination.fromJson(data['pagination']);
        }
      }
    } else if (json.containsKey('data') && json['data'] is List) {
      // Direct array format: { data: [...] }
      productsList = json['data'];
    } else if (json.containsKey('products') && json['products'] is List) {
      // Old format: { products: [...], pagination: {...} }
      productsList = json['products'];
      if (json['pagination'] is Map<String, dynamic>) {
        paging = Pagination.fromJson(json['pagination']);
      }
    }

    return ProductListResponse(productsData: productsList, pagination: paging);
  }
}
