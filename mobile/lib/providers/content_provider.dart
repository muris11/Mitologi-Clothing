import 'package:flutter/material.dart';
import '../models/page.dart';
import '../models/landing_page_data.dart';
import '../models/menu.dart';
import '../models/material.dart' as material_model;
import '../models/order_step.dart';
import '../services/content_service.dart';

class ContentProvider with ChangeNotifier {
  final ContentService _service = ContentService();

  // Landing Page
  LandingPageData? _landingPage;
  bool _isLoadingLanding = false;
  String? _landingError;

  // Single Page
  PageData? _currentPage;
  bool _isLoadingPage = false;
  String? _pageError;

  // All Pages
  List<PageData> _pages = [];
  bool _isLoadingPages = false;
  String? _pagesError;

  // Menu (Backend: MenuController)
  List<MenuItem> _menuItems = [];
  bool _isLoadingMenu = false;
  String? _menuError;

  // Materials (Backend: MaterialController)
  List<material_model.Material> _materials = [];
  bool _isLoadingMaterials = false;
  String? _materialsError;

  // Order Steps (Backend: OrderStepController)
  List<OrderStep> _orderSteps = [];
  bool _isLoadingOrderSteps = false;
  String? _orderStepsError;

  // Getters
  LandingPageData? get landingPage => _landingPage;
  bool get isLoadingLanding => _isLoadingLanding;
  String? get landingError => _landingError;

  PageData? get currentPage => _currentPage;
  bool get isLoadingPage => _isLoadingPage;
  String? get pageError => _pageError;

  List<PageData> get pages => _pages;
  bool get isLoadingPages => _isLoadingPages;
  String? get pagesError => _pagesError;

  List<MenuItem> get menuItems => _menuItems;
  bool get isLoadingMenu => _isLoadingMenu;
  String? get menuError => _menuError;

  List<material_model.Material> get materials => _materials;
  bool get isLoadingMaterials => _isLoadingMaterials;
  String? get materialsError => _materialsError;

  List<OrderStep> get orderSteps => _orderSteps;
  bool get isLoadingOrderSteps => _isLoadingOrderSteps;
  String? get orderStepsError => _orderStepsError;

  // Landing Page
  Future<void> fetchLandingPage() async {
    if (_landingPage != null && !_isLoadingLanding) return;

    _isLoadingLanding = true;
    _landingError = null;
    notifyListeners();

    try {
      _landingPage = await _service.getLandingPage();
    } catch (e) {
      _landingError = e.toString();
    } finally {
      _isLoadingLanding = false;
      notifyListeners();
    }
  }

  // Single Page
  Future<void> fetchPage(String slug) async {
    _isLoadingPage = true;
    _pageError = null;
    _currentPage = null;
    notifyListeners();

    try {
      _currentPage = await _service.getPage(slug);
    } catch (e) {
      _pageError = e.toString();
    } finally {
      _isLoadingPage = false;
      notifyListeners();
    }
  }

  // All Pages
  Future<void> fetchPages() async {
    if (_pages.isNotEmpty && !_isLoadingPages) return;

    _isLoadingPages = true;
    _pagesError = null;
    notifyListeners();

    try {
      _pages = await _service.getPages();
    } catch (e) {
      _pagesError = e.toString();
    } finally {
      _isLoadingPages = false;
      notifyListeners();
    }
  }

  // Menu (Backend: MenuController@show)
  Future<void> fetchMenu(String handle) async {
    _isLoadingMenu = true;
    _menuError = null;
    _menuItems = [];
    notifyListeners();

    try {
      _menuItems = await _service.getMenu(handle);
    } catch (e) {
      _menuError = e.toString();
    } finally {
      _isLoadingMenu = false;
      notifyListeners();
    }
  }

  // Materials (Backend: MaterialController@index)
  Future<void> fetchMaterials() async {
    if (_materials.isNotEmpty && !_isLoadingMaterials) return;

    _isLoadingMaterials = true;
    _materialsError = null;
    notifyListeners();

    try {
      _materials = await _service.getMaterials();
    } catch (e) {
      _materialsError = e.toString();
    } finally {
      _isLoadingMaterials = false;
      notifyListeners();
    }
  }

  // Order Steps (Backend: OrderStepController@index)
  Future<void> fetchOrderSteps({String? type, bool grouped = false}) async {
    _isLoadingOrderSteps = true;
    _orderStepsError = null;
    notifyListeners();

    try {
      _orderSteps = await _service.getOrderSteps(type: type, grouped: grouped);
    } catch (e) {
      _orderStepsError = e.toString();
    } finally {
      _isLoadingOrderSteps = false;
      notifyListeners();
    }
  }
}
