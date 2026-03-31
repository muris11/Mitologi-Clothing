import 'package:flutter/material.dart';
import '../services/chatbot_service.dart';

class ChatMessage {
  final String text;
  final bool isUser;
  final DateTime timestamp;

  ChatMessage({
    required this.text,
    required this.isUser,
    required this.timestamp,
  });
}

class ChatbotProvider with ChangeNotifier {
  final ChatbotService _service = ChatbotService();

  final List<ChatMessage> _messages = [];
  bool _isTyping = false;
  String? _error;

  List<ChatMessage> get messages => _messages;
  bool get isTyping => _isTyping;
  String? get error => _error;

  void addInitialGreeting(String? productId) {
    if (_messages.isEmpty) {
      String greeting = 'Halo! Ada yang bisa saya bantu?';
      if (productId != null) {
        greeting = 'Halo! Ada pertanyaan seputar produk ini?';
      }
      _messages.add(
        ChatMessage(text: greeting, isUser: false, timestamp: DateTime.now()),
      );
      notifyListeners();
    }
  }

  Future<void> sendMessage(String text) async {
    if (text.trim().isEmpty) return;

    // Add user message
    _messages.add(
      ChatMessage(text: text.trim(), isUser: true, timestamp: DateTime.now()),
    );
    _isTyping = true;
    _error = null;
    notifyListeners();

    try {
      final reply = await _service.sendMessage(text.trim());
      // Add bot reply
      _messages.add(
        ChatMessage(text: reply, isUser: false, timestamp: DateTime.now()),
      );
    } catch (e) {
      _error = e.toString();
      _messages.add(
        ChatMessage(
          text: 'Maaf, terjadi kesalahan atau koneksi terputus.',
          isUser: false,
          timestamp: DateTime.now(),
        ),
      );
    } finally {
      _isTyping = false;
      notifyListeners();
    }
  }

  void clearChat() {
    _messages.clear();
    _error = null;
    _isTyping = false;
    notifyListeners();
  }
}
