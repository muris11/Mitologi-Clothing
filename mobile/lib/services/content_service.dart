import '../models/page.dart';
import '../models/landing_page_data.dart';
import '../models/menu.dart';
import '../models/material.dart';
import '../models/order_step.dart';
import 'api_service.dart';

class ContentService {
  ContentService({ApiService? api}) : _api = api ?? ApiService();

  final ApiService _api;

  /// Safe helper to convert response to Map
  Map<String, dynamic> _safeToMap(dynamic response) {
    if (response == null) return {};
    if (response is Map<String, dynamic>) return response;
    if (response is Map) return Map<String, dynamic>.from(response);
    return {};
  }

  /// Safe helper to get List from response
  List<dynamic> _safeToList(dynamic response, {String? dataKey}) {
    if (response == null) return [];

    // Try to get from data key first
    if (dataKey != null && response is Map) {
      final data = response[dataKey];
      if (data is List) return data;
    }

    // Check if response has data field (common Laravel format)
    if (response is Map<String, dynamic> && response['data'] is List) {
      return response['data'] as List<dynamic>;
    }

    // Response is already a list
    if (response is List) return response;

    return [];
  }

  Future<LandingPageData> getLandingPage() async {
    final response = await _api.get('/landing-page');

    if (response == null) {
      throw ApiException('Failed to load landing page', 500);
    }

    // Handle {data: {...}} format or direct response
    Map<String, dynamic> data;
    if (response is Map<String, dynamic> && response['data'] is Map) {
      data = Map<String, dynamic>.from(response['data'] as Map);
    } else if (response is Map) {
      data = Map<String, dynamic>.from(response);
    } else {
      throw ApiException('Invalid response format', 500);
    }

    return LandingPageData.fromJson(data);
  }

  Future<PageData> getPage(String slug) async {
    final response = await _api.get('/pages/$slug');

    if (response == null) {
      throw ApiException('Page not found', 404);
    }

    final map = _safeToMap(response);

    // Try {data: {...}} format first
    final data = map['data'];
    if (data is Map) {
      return PageData.fromJson(Map<String, dynamic>.from(data));
    }

    // Direct page response
    return PageData.fromJson(map);
  }

  Future<List<PageData>> getPages() async {
    final response = await _api.get('/pages');
    final data = _safeToList(response, dataKey: 'data');

    return data
        .whereType<Map<String, dynamic>>()
        .map((e) => PageData.fromJson(e))
        .toList();
  }

  // ===== Site Settings Service (Backend: SiteSettingsController) =====

  /// Get site configuration and settings
  Future<SiteSettings?> getSiteSettings() async {
    try {
      final response = await _api.get('/site-settings');
      if (response == null) return null;

      final map = _safeToMap(response);
      final data = map['data'] ?? map;

      if (data is Map) {
        return SiteSettings.fromJson(Map<String, dynamic>.from(data));
      }
      return null;
    } on Exception {
      return null;
    }
  }

  // ===== Portfolio Service (Backend: PortfolioController) =====

  /// Get all portfolio items
  Future<List<PortfolioItem>> getPortfolios() async {
    try {
      final response = await _api.get('/portfolios');
      final data = _safeToList(response, dataKey: 'data');

      return data
          .whereType<Map<String, dynamic>>()
          .map((e) => PortfolioItem.fromJson(e))
          .toList();
    } on Exception {
      return [];
    }
  }

  /// Get portfolio detail by slug
  Future<PortfolioItem> getPortfolioDetail(String slug) async {
    final response = await _api.get('/portfolios/$slug');
    if (response == null) {
      throw ApiException('Portfolio not found', 404);
    }

    final map = _safeToMap(response);
    final data = map['data'] ?? map;

    return PortfolioItem.fromJson(Map<String, dynamic>.from(data as Map));
  }

  // ===== Menu Service (Backend: MenuController) =====

  /// Get menu items by handle
  Future<List<MenuItem>> getMenu(String handle) async {
    try {
      final response = await _api.get('/menus/$handle');
      final data = _safeToList(response, dataKey: 'data');

      return data
          .whereType<Map<String, dynamic>>()
          .map((e) => MenuItem.fromJson(e))
          .toList();
    } on Exception {
      return [];
    }
  }

  // ===== Material Service (Backend: MaterialController) =====

  /// Get all materials
  Future<List<MaterialInfo>> getMaterials() async {
    try {
      final response = await _api.get('/materials');
      final data = _safeToList(response, dataKey: 'data');

      return data
          .whereType<Map<String, dynamic>>()
          .map((e) => MaterialInfo.fromJson(e))
          .toList();
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

      final List<OrderStep> allSteps = [];

      if (response is List) {
        // Direct list response
        allSteps.addAll(
          response.whereType<Map<String, dynamic>>().map(
            (e) => OrderStep.fromJson(e),
          ),
        );
      } else if (response is Map<String, dynamic> && grouped) {
        // Handle grouped response
        final wrapped = response['data'];
        if (wrapped is Map<String, dynamic>) {
          wrapped.forEach((key, value) {
            if (value is List) {
              allSteps.addAll(
                value.whereType<Map<String, dynamic>>().map(
                  (e) => OrderStep.fromJson(e),
                ),
              );
            }
          });
        }
      } else {
        // Try standard {data: [...]} format
        final data = _safeToList(response, dataKey: 'data');
        allSteps.addAll(
          data.whereType<Map<String, dynamic>>().map(
            (e) => OrderStep.fromJson(e),
          ),
        );
      }

      return allSteps;
    } on Exception {
      return [];
    }
  }
}
