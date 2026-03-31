import 'dart:async';
import 'api_service.dart';

class ChatbotService {
  final ApiService _api = ApiService();

  Future<String> sendMessage(String message) async {
    try {
      final response = await _api
          .post('/chatbot', body: {'message': message}, requiresAuth: true)
          .timeout(const Duration(seconds: 15));

      return response['reply'] ??
          response['response'] ??
          'Maaf, saya tidak mengerti.';
    } on TimeoutException {
      return 'Maaf, server butuh waktu terlalu lama untuk merespons. Silakan coba lagi.';
    } catch (e) {
      if (e.toString().contains('TimeoutException')) {
        return 'Maaf, server butuh waktu terlalu lama untuk merespons. Silakan coba lagi.';
      }
      rethrow;
    }
  }
}
