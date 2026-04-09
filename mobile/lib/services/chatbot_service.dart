import 'dart:async';
import 'api_service.dart';

class ChatbotService {
  ChatbotService({ApiService? api}) : _api = api ?? ApiService();

  final ApiService _api;

  Future<String> sendMessage(
    String message, {
    List<Map<String, String>> history = const [],
  }) async {
    try {
      final body = <String, dynamic>{'message': message};

      if (history.isNotEmpty) {
        body['history'] = history;
      }

      final response = await _api
          .post('/chatbot', body: body, requiresAuth: false)
          .timeout(const Duration(seconds: 15));

      final data = response is Map<String, dynamic>
          ? (response['data'] ?? response)
          : response;

      if (data is Map<String, dynamic>) {
        return (data['reply'] ??
                data['response'] ??
                'Maaf, saya tidak mengerti.')
            .toString();
      }
      if (response is Map<String, dynamic>) {
        return (response['reply'] ??
                response['response'] ??
                'Maaf, saya tidak mengerti.')
            .toString();
      }
      return 'Maaf, saya tidak mengerti.';
    } on TimeoutException {
      return 'Maaf, server butuh waktu terlalu lama untuk merespons. Silakan coba lagi.';
    } on Exception catch (e) {
      if (e.toString().contains('TimeoutException')) {
        return 'Maaf, server butuh waktu terlalu lama untuk merespons. Silakan coba lagi.';
      }
      rethrow;
    }
  }
}
