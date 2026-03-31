import '../models/page.dart';
import '../models/landing_page_data.dart';
import '../models/menu.dart';
import '../models/material.dart';
import '../models/order_step.dart';
import 'api_service.dart';

class ContentService {
  final ApiService _api = ApiService();

  Future<LandingPageData> getLandingPage() async {
    final response = await _api.get('/landing-page');
    return LandingPageData.fromJson(response);
  }

  Future<PageData> getPage(String slug) async {
    final response = await _api.get('/pages/$slug');
    if (response != null && response['data'] != null) {
      return PageData.fromJson(response['data']);
    }
    return PageData.fromJson(response);
  }

  Future<List<PageData>> getPages() async {
    final response = await _api.get('/pages');
    if (response is List) {
      return response.map((json) => PageData.fromJson(json)).toList();
    } else if (response != null && response['data'] != null) {
      return (response['data'] as List)
          .map((json) => PageData.fromJson(json))
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
        return response.map((json) => MenuItem.fromJson(json)).toList();
      }
      return [];
    } catch (e) {
      return [];
    }
  }

  // ===== Material Service (Backend: MaterialController) =====

  /// Get all materials
  Future<List<Material>> getMaterials() async {
    try {
      final response = await _api.get('/materials');

      if (response is List) {
        return response.map((json) => Material.fromJson(json)).toList();
      } else if (response != null && response['data'] != null) {
        return (response['data'] as List)
            .map((json) => Material.fromJson(json))
            .toList();
      }
      return [];
    } catch (e) {
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
      List<String> queryParams = [];
      if (type != null) {
        queryParams.add('type=$type');
      }
      if (grouped) {
        queryParams.add('grouped=true');
      }

      final queryString = queryParams.isNotEmpty
          ? '?${queryParams.join('&')}'
          : '';
      final response = await _api.get('/order-steps$queryString');

      if (response is List) {
        return response.map((json) => OrderStep.fromJson(json)).toList();
      } else if (response is Map<String, dynamic> && grouped) {
        // Handle grouped response
        List<OrderStep> allSteps = [];
        response.forEach((key, value) {
          if (value is List) {
            allSteps.addAll(value.map((json) => OrderStep.fromJson(json)));
          }
        });
        return allSteps;
      }
      return [];
    } catch (e) {
      return [];
    }
  }
}
