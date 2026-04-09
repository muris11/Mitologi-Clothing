import '../models/page.dart';
import '../models/landing_page_data.dart';
import '../models/menu.dart';
import '../models/material.dart';
import '../models/order_step.dart';
import 'api_service.dart';

class ContentService {
  ContentService({ApiService? api}) : _api = api ?? ApiService();

  final ApiService _api;

  Future<LandingPageData> getLandingPage() async {
    final response = await _api.get('/landing-page');
    final data = response is Map<String, dynamic> ? response['data'] : response;
    return LandingPageData.fromJson(data as Map<String, dynamic>);
  }

  Future<PageData> getPage(String slug) async {
    final response = await _api.get('/pages/$slug');
    if (response is Map<String, dynamic> && response['data'] is Map) {
      return PageData.fromJson(
        Map<String, dynamic>.from(response['data'] as Map),
      );
    }
    return PageData.fromJson(Map<String, dynamic>.from(response as Map));
  }

  Future<List<PageData>> getPages() async {
    final response = await _api.get('/pages');
    if (response is List) {
      return response
          .map(
            (json) => PageData.fromJson(Map<String, dynamic>.from(json as Map)),
          )
          .toList();
    } else if (response is Map<String, dynamic> && response['data'] is List) {
      return (response['data'] as List)
          .map(
            (json) => PageData.fromJson(Map<String, dynamic>.from(json as Map)),
          )
          .toList();
    }
    return [];
  }

  // ===== Menu Service (Backend: MenuController) =====

  /// Get menu items by handle
  Future<List<MenuItem>> getMenu(String handle) async {
    try {
      final response = await _api.get('/menus/$handle');

      if (response is List) {
        return response
            .map(
              (json) =>
                  MenuItem.fromJson(Map<String, dynamic>.from(json as Map)),
            )
            .toList();
      } else if (response is Map<String, dynamic> && response['data'] is List) {
        return (response['data'] as List)
            .map(
              (json) =>
                  MenuItem.fromJson(Map<String, dynamic>.from(json as Map)),
            )
            .toList();
      }
      return [];
    } on Exception {
      return [];
    }
  }

  // ===== Material Service (Backend: MaterialController) =====

  /// Get all materials
  Future<List<Material>> getMaterials() async {
    try {
      final response = await _api.get('/materials');

      if (response is List) {
        return response
            .map(
              (json) =>
                  Material.fromJson(Map<String, dynamic>.from(json as Map)),
            )
            .toList();
      } else if (response is Map<String, dynamic> && response['data'] is List) {
        return (response['data'] as List)
            .map(
              (json) =>
                  Material.fromJson(Map<String, dynamic>.from(json as Map)),
            )
            .toList();
      }
      return [];
    } on Exception {
      return [];
    }
  }

  // ===== Order Step Service (Backend: OrderStepController) =====

  /// Get order steps (cara pemesanan)
  Future<List<OrderStep>> getOrderSteps({
    String? type,
    bool grouped = false,
  }) async {
    try {
      final queryParams = <String, dynamic>{};
      if (type != null) {
        queryParams['type'] = type;
      }
      if (grouped) {
        queryParams['grouped'] = 'true';
      }
      final response = await _api.get('/order-steps', queryParams: queryParams);

      if (response is List) {
        return response
            .map(
              (json) =>
                  OrderStep.fromJson(Map<String, dynamic>.from(json as Map)),
            )
            .toList();
      } else if (response is Map<String, dynamic> && grouped) {
        // Handle grouped response
        final wrapped = response['data'];
        final groupedData = wrapped is Map<String, dynamic>
            ? wrapped
            : response;
        final List<OrderStep> allSteps = [];
        groupedData.forEach((key, value) {
          if (value is List) {
            allSteps.addAll(
              value.map(
                (json) =>
                    OrderStep.fromJson(Map<String, dynamic>.from(json as Map)),
              ),
            );
          }
        });
        return allSteps;
      }
      return [];
    } on Exception {
      return [];
    }
  }
}
