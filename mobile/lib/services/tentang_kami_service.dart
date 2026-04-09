import 'api_service.dart';
import '../models/tentang_kami/site_settings_model.dart';
import '../models/tentang_kami/team_member_model.dart';

class TentangKamiService {
  TentangKamiService({ApiService? api}) : _api = api ?? ApiService();

  final ApiService _api;

  Future<SiteSettings?> getSiteSettings() async {
    try {
      final response = await _api.get('/site-settings');
      final wrapped = response is Map<String, dynamic>
          ? response
          : Map<String, dynamic>.from(response as Map);
      final data = wrapped['data'] is Map<String, dynamic>
          ? wrapped['data'] as Map<String, dynamic>
          : wrapped;
      if (data.isEmpty) {
        return null;
      }
      return SiteSettings.fromJson(data);
    } on Exception {
      // Error fetching site settings
      return null;
    }
  }

  Future<List<TeamMember>> getTeamMembers() async {
    try {
      final response = await _api.get('/landing-page');
      final data = response is Map<String, dynamic>
          ? response['data'] ?? response
          : response;
      if (data == null || (data is Map && data.isEmpty)) {
        return [];
      }
      final payload = Map<String, dynamic>.from(data as Map);
      final List<dynamic> members =
          (payload['teamMembers'] ?? payload['team_members'] ?? <dynamic>[])
              as List<dynamic>;
      return members
          .map((m) => TeamMember.fromJson(Map<String, dynamic>.from(m as Map)))
          .toList();
    } on Exception {
      // Error fetching team members
      return [];
    }
  }

  // Alternative method using single endpoint
  Future<Map<String, dynamic>> getAllData() async {
    try {
      final response = await _api.get('/landing-page');
      final data = response is Map<String, dynamic>
          ? response['data'] ?? response
          : response;
      if (data == null || (data is Map && data.isEmpty)) {
        throw Exception('No data received from server');
      }
      return Map<String, dynamic>.from(data as Map);
    } on Exception {
      // Error fetching landing page data
      rethrow;
    }
  }
}
