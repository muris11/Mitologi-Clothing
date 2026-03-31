import 'package:flutter/material.dart';
import 'package:url_launcher/url_launcher.dart';
import '../../../config/theme.dart';

class ContactCTASection extends StatelessWidget {
  final String? whatsappNumber;
  final String? buttonText;
  final String? title;
  final String? subtitle;

  const ContactCTASection({
    super.key,
    this.whatsappNumber,
    this.buttonText,
    this.title,
    this.subtitle,
  });

  Future<void> _openWhatsApp(BuildContext context) async {
    if (whatsappNumber == null || whatsappNumber!.isEmpty) return;

    final cleanNumber = whatsappNumber!.replaceAll(RegExp(r'[^0-9]'), '');
    final url = Uri.parse('https://wa.me/$cleanNumber');

    try {
      if (await canLaunchUrl(url)) {
        await launchUrl(url, mode: LaunchMode.externalApplication);
      } else {
        if (context.mounted) {
          ScaffoldMessenger.of(context).showSnackBar(
            const SnackBar(content: Text('Tidak dapat membuka WhatsApp')),
          );
        }
      }
    } catch (e) {
      // Error handled silently in production
    }
  }

  @override
  Widget build(BuildContext context) {
    // Don't show if no WhatsApp number from API
    if (whatsappNumber == null || whatsappNumber!.isEmpty) {
      return const SizedBox.shrink();
    }

    return Container(
      color: AppTheme.primary,
      padding: const EdgeInsets.symmetric(vertical: 48, horizontal: 24),
      child: Column(
        children: [
          if (title != null && title!.isNotEmpty)
            Text(
              title!,
              style: const TextStyle(
                fontSize: 24,
                fontWeight: FontWeight.bold,
                color: Colors.white,
              ),
            ),
          if (title != null &&
              title!.isNotEmpty &&
              subtitle != null &&
              subtitle!.isNotEmpty)
            const SizedBox(height: 16),
          if (subtitle != null && subtitle!.isNotEmpty)
            Text(
              subtitle!,
              style: TextStyle(
                fontSize: 14,
                color: Colors.white.withValues(alpha: 0.8),
              ),
            ),
          const SizedBox(height: 32),
          ElevatedButton.icon(
            onPressed: () => _openWhatsApp(context),
            icon: const Icon(Icons.chat, color: AppTheme.primary),
            label: Text(
              buttonText ?? 'Chat WhatsApp',
              style: const TextStyle(
                color: AppTheme.primary,
                fontWeight: FontWeight.w600,
              ),
            ),
            style: ElevatedButton.styleFrom(
              backgroundColor: AppTheme.accent,
              padding: const EdgeInsets.symmetric(horizontal: 32, vertical: 16),
              shape: RoundedRectangleBorder(
                borderRadius: BorderRadius.circular(30),
              ),
            ),
          ),
        ],
      ),
    );
  }
}
