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

    if (json.containsKey('data') && json['data'] is List) {
      productsList = json['data'];
      paging = Pagination.fromJson(json);
    } else if (json.containsKey('products') && json['products'] is List) {
      productsList = json['products'];
      if (json['pagination'] is Map<String, dynamic>) {
        paging = Pagination.fromJson(json['pagination']);
      }
    } else if (json.containsKey('products') &&
        json['products'] is Map &&
        json['products'].containsKey('data')) {
      productsList = json['products']['data'];
      paging = Pagination.fromJson(json['products']);
    }

    return ProductListResponse(productsData: productsList, pagination: paging);
  }
}
