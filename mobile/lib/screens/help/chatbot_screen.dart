import 'package:flutter/material.dart';
import '../../config/theme.dart';
import 'dart:async';

import 'package:provider/provider.dart';
import '../../providers/chatbot_provider.dart';
import '../../widgets/animations/typing_indicator.dart';
import '../../widgets/common/mitologi_scaffold.dart';

class ChatbotScreen extends StatefulWidget {
  final String? productId; // Optional, to give context if starting from PDP

  const ChatbotScreen({super.key, this.productId});

  @override
  State<ChatbotScreen> createState() => _ChatbotScreenState();
}

class _ChatbotScreenState extends State<ChatbotScreen>
    with TickerProviderStateMixin {
  late AnimationController _fadeController;
  final TextEditingController _textController = TextEditingController();
  final ScrollController _scrollController = ScrollController();

  @override
  void initState() {
    super.initState();
    _fadeController = AnimationController(
      vsync: this,
      duration: const Duration(milliseconds: 600),
    );
    _fadeController.forward();

    // Initial bot greeting via Provider
    WidgetsBinding.instance.addPostFrameCallback((_) {
      context.read<ChatbotProvider>().addInitialGreeting(widget.productId);
    });
  }

  @override
  void dispose() {
    _textController.dispose();
    _scrollController.dispose();
    _fadeController.dispose();
    super.dispose();
  }

  void _handleSubmitted(String text) async {
    if (text.trim().isEmpty) return;

    _textController.clear();
    _scrollToBottom();

    await context.read<ChatbotProvider>().sendMessage(text);
    _scrollToBottom();
  }

  void _scrollToBottom() {
    WidgetsBinding.instance.addPostFrameCallback((_) {
      if (_scrollController.hasClients) {
        _scrollController.animateTo(
          _scrollController.position.maxScrollExtent + 100,
          duration: const Duration(milliseconds: 300),
          curve: Curves.easeOut,
        );
      }
    });
  }

  @override
  Widget build(BuildContext context) {
    return MitologiScaffold(
      title: 'Mitologi Care',
      subtitle:
          'Asisten belanja yang siap bantu cari produk dan jawab pertanyaan Anda.',
      showLogo: false,
      bodyPadding: EdgeInsets.zero,
      leading: IconButton(
        tooltip: 'Back',
        icon: const Icon(Icons.arrow_back, color: AppTheme.primary),
        onPressed: () => Navigator.pop(context),
      ),
      body: SafeArea(
        child: Consumer<ChatbotProvider>(
          builder: (context, provider, child) {
            final messages = provider.messages;
            final isTyping = provider.isTyping;

            return Column(
              children: [
                if (provider.error != null)
                  Container(
                    padding: const EdgeInsets.all(8),
                    color: AppTheme.error.withValues(alpha: 0.1),
                    width: double.infinity,
                    child: Text(
                      provider.error!,
                      style: const TextStyle(
                        color: AppTheme.error,
                        fontSize: 12,
                      ),
                      textAlign: TextAlign.center,
                    ),
                  ),
                Expanded(
                  child: FadeTransition(
                    opacity: _fadeController,
                    child: ListView.builder(
                      controller: _scrollController,
                      padding: const EdgeInsets.all(20),
                      itemCount: messages.length + (isTyping ? 1 : 0),
                      itemBuilder: (context, index) {
                        if (index == messages.length && isTyping) {
                          return _buildTypingIndicator();
                        }
                        final message = messages[index];
                        return _buildMessageBubble(message);
                      },
                    ),
                  ),
                ),
                _buildMessageInput(),
              ],
            );
          },
        ),
      ),
    );
  }

  Widget _buildMessageBubble(ChatMessage message) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 20),
      child: Row(
        mainAxisAlignment: message.isUser
            ? MainAxisAlignment.end
            : MainAxisAlignment.start,
        crossAxisAlignment: CrossAxisAlignment.end,
        children: [
          if (!message.isUser) ...[
            Container(
              margin: const EdgeInsets.only(right: 12),
              padding: const EdgeInsets.all(8),
              decoration: BoxDecoration(
                color: AppTheme.primary,
                shape: BoxShape.circle,
              ),
              child: const Icon(
                Icons.support_agent,
                size: 18,
                color: Colors.white,
              ),
            ),
          ],
          Flexible(
            child: Container(
              padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 14),
              decoration: BoxDecoration(
                color: message.isUser
                    ? AppTheme.primary
                    : AppTheme.surfaceContainerLowest,
                borderRadius: BorderRadius.only(
                  topLeft: const Radius.circular(24),
                  topRight: const Radius.circular(24),
                  bottomLeft: Radius.circular(message.isUser ? 24 : 4),
                  bottomRight: Radius.circular(message.isUser ? 4 : 24),
                ),
              ),
              child: Text(
                message.text,
                style: TextStyle(
                  color: message.isUser ? Colors.white : AppTheme.slate800,
                  fontSize: 14,
                  height: 1.4,
                ),
              ),
            ),
          ),
          if (message.isUser) ...[
            Container(
              margin: const EdgeInsets.only(left: 12),
              padding: const EdgeInsets.all(8),
              decoration: BoxDecoration(
                color: AppTheme.accent,
                shape: BoxShape.circle,
              ),
              child: const Icon(Icons.person, size: 18, color: Colors.white),
            ),
          ],
        ],
      ),
    );
  }

  Widget _buildTypingIndicator() {
    return Padding(
      padding: const EdgeInsets.only(bottom: 20),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.start,
        crossAxisAlignment: CrossAxisAlignment.end,
        children: [
          Container(
            margin: const EdgeInsets.only(right: 12),
            padding: const EdgeInsets.all(8),
            decoration: BoxDecoration(
              color: AppTheme.primary,
              shape: BoxShape.circle,
            ),
            child: const Icon(
              Icons.support_agent,
              size: 18,
              color: Colors.white,
            ),
          ),
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 16),
            decoration: const BoxDecoration(
              color: AppTheme.surfaceContainerLowest,
              borderRadius: BorderRadius.only(
                topLeft: Radius.circular(24),
                topRight: Radius.circular(24),
                bottomLeft: Radius.circular(4),
                bottomRight: Radius.circular(24),
              ),
            ),
            child: const BouncingDotsIndicator(
              color: AppTheme.slate400,
              dotSize: 8.0,
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildMessageInput() {
    return Container(
      padding: const EdgeInsets.all(20),
      decoration: const BoxDecoration(
        color: AppTheme.surfaceContainerLowest,
        borderRadius: BorderRadius.vertical(top: Radius.circular(24)),
      ),
      child: Row(
        children: [
          Expanded(
            child: TextField(
              controller: _textController,
              onSubmitted: _handleSubmitted,
              style: const TextStyle(fontSize: 14),
              decoration: InputDecoration(
                hintText: 'Ketik pesan Anda...',
                hintStyle: const TextStyle(color: AppTheme.slate400),
                filled: true,
                fillColor: AppTheme.surfaceContainerLow,
                contentPadding: const EdgeInsets.symmetric(
                  horizontal: 20,
                  vertical: 16,
                ),
                border: OutlineInputBorder(
                  borderRadius: BorderRadius.circular(30),
                  borderSide: BorderSide.none,
                ),
              ),
            ),
          ),
          const SizedBox(width: 12),
          Container(
            decoration: const BoxDecoration(
              color: AppTheme.accent,
              shape: BoxShape.circle,
            ),
            child: IconButton(
              icon: const Icon(Icons.send, color: Colors.white, size: 20),
              onPressed: () => _handleSubmitted(_textController.text),
            ),
          ),
        ],
      ),
    );
  }
}

class _DotIndicator extends StatefulWidget {
  final int delay;

  const _DotIndicator({required this.delay});

  @override
  State<_DotIndicator> createState() => _DotIndicatorState();
}

class _DotIndicatorState extends State<_DotIndicator>
    with SingleTickerProviderStateMixin {
  late AnimationController _controller;
  late Animation<double> _animation;

  @override
  void initState() {
    super.initState();
    _controller = AnimationController(
      vsync: this,
      duration: const Duration(milliseconds: 600),
    );
    _animation = Tween<double>(begin: 0.0, end: -8.0).animate(
      CurvedAnimation(parent: _controller, curve: Curves.easeInOutSine),
    );

    Future.delayed(Duration(milliseconds: widget.delay), () {
      if (mounted) {
        _controller.repeat(reverse: true);
      }
    });
  }

  @override
  void dispose() {
    _controller.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return AnimatedBuilder(
      animation: _animation,
      builder: (context, child) {
        return Transform.translate(
          offset: Offset(0, _animation.value),
          child: Container(
            width: 6,
            height: 6,
            decoration: const BoxDecoration(
              color: AppTheme.slate400,
              shape: BoxShape.circle,
            ),
          ),
        );
      },
    );
  }
}
