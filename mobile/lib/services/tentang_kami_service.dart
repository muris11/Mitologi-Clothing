import 'api_service.dart';
import '../models/tentang_kami/site_settings_model.dart';
import '../models/tentang_kami/team_member_model.dart';

class TentangKamiService {
  final ApiService _api = ApiService();

  Future<SiteSettings?> getSiteSettings() async {
    try {
      final Map<String, dynamic> response = await _api.get('/site-settings');
      if (response.isEmpty) {
        return null;
      }
      return SiteSettings.fromJson(response);
    } catch (e) {
      // Error fetching site settings
      return null;
    }
  }

  Future<List<TeamMember>> getTeamMembers() async {
    try {
      final response = await _api.get('/landing-page');
      if (response == null || response.isEmpty) {
        return [];
      }
      final List<dynamic> members = response['team_members'] ?? [];
      return members.map((m) => TeamMember.fromJson(m)).toList();
    } catch (e) {
      // Error fetching team members
      return [];
    }
  }

  // Alternative method using single endpoint
  Future<Map<String, dynamic>> getAllData() async {
    try {
      final response = await _api.get('/landing-page');
      if (response == null || response.isEmpty) {
        throw Exception('No data received from server');
      }
      return response;
    } catch (e) {
      // Error fetching landing page data
      rethrow;
    }
  }
}
