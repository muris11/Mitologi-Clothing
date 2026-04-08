import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import 'package:provider/provider.dart';

import '../../config/theme.dart';
import '../../config/auth_styles.dart';
import '../../providers/auth_provider.dart';
import '../../widgets/common/auth_page_shell.dart';
import '../../widgets/auth/auth_button.dart';
import '../../utils/responsive_helper.dart';

class ForgotPasswordScreen extends StatefulWidget {
  const ForgotPasswordScreen({super.key});

  @override
  State<ForgotPasswordScreen> createState() => _ForgotPasswordScreenState();
}

class _ForgotPasswordScreenState extends State<ForgotPasswordScreen>
    with SingleTickerProviderStateMixin {
  final _emailController = TextEditingController();
  final _formKey = GlobalKey<FormState>();
  bool _isSubmitted = false;
  bool _isLoading = false;

  late AnimationController _animController;

  @override
  void initState() {
    super.initState();
    _animController = AnimationController(
      vsync: this,
      duration: const Duration(milliseconds: 600),
    );
    _animController.forward();
  }

  @override
  void dispose() {
    _animController.dispose();
    _emailController.dispose();
    super.dispose();
  }

  Future<void> _submit() async {
    if (!_formKey.currentState!.validate()) return;

    setState(() => _isLoading = true);
    final success = await context.read<AuthProvider>().forgotPassword(
      _emailController.text.trim(),
    );

    if (!mounted) return;

    setState(() {
      _isLoading = false;
      _isSubmitted = success;
    });

    if (!success) {
      final error =
          context.read<AuthProvider>().error ??
          'Gagal mengirim link reset password';
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text(error),
          backgroundColor: AppTheme.error,
          behavior: SnackBarBehavior.floating,
          shape: RoundedRectangleBorder(borderRadius: AppTheme.radius16),
        ),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return AuthPageShell(
      title: 'Lupa Password?',
      subtitle:
          'Masukkan email Anda dan kami akan mengirimkan link untuk mereset password.',
      child: _isSubmitted ? _buildSuccessState() : _buildFormState(),
    );
  }

  Widget _buildFormState() {
    return Form(
      key: _formKey,
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.stretch,
        children: [
          Container(
            width: 80,
            height: 80,
            decoration: BoxDecoration(
              color: AppTheme.cream,
              shape: BoxShape.circle,
              border: Border.all(
                color: AppTheme.accent.withValues(alpha: 0.3),
                width: 1,
              ),
            ),
            child: const Icon(
              Icons.lock_reset,
              size: 36,
              color: AppTheme.accent,
            ),
          ),
          const SizedBox(height: 32),
          Text('Email', style: AuthStyles.labelStyle),
          const SizedBox(height: 8),
          TextFormField(
            controller: _emailController,
            keyboardType: TextInputType.emailAddress,
            style: AuthStyles.inputStyle,
            decoration: InputDecoration(
              hintText: 'Masukkan email Anda',
              hintStyle: TextStyle(
                color: AppTheme.onSurfaceVariant.withValues(alpha: 0.7),
                fontSize: AuthStyles.inputSize,
              ),
              filled: true,
              fillColor: AppTheme.cream,
              prefixIcon: const Icon(
                Icons.email_outlined,
                size: 20,
                color: AppTheme.onSurfaceVariant,
              ),
              contentPadding: EdgeInsets.symmetric(
                horizontal: ResponsiveHelper.horizontalPadding(context),
                vertical: 14,
              ),
              border: OutlineInputBorder(
                borderRadius: BorderRadius.circular(AuthStyles.inputRadius),
                borderSide: BorderSide(color: AuthStyles.inputBorderColor),
              ),
              enabledBorder: OutlineInputBorder(
                borderRadius: BorderRadius.circular(AuthStyles.inputRadius),
                borderSide: BorderSide(color: AuthStyles.inputBorderColor),
              ),
              focusedBorder: OutlineInputBorder(
                borderRadius: BorderRadius.circular(AuthStyles.inputRadius),
                borderSide: const BorderSide(
                  color: AppTheme.primary,
                  width: 1.5,
                ),
              ),
            ),
            validator: (value) {
              if (value == null || value.isEmpty || !value.contains('@')) {
                return 'Masukkan email yang valid';
              }
              return null;
            },
          ),
          const SizedBox(height: AuthStyles.buttonSpacing),
          AuthButton(
            text: 'Kirim Link Reset',
            isLoading: _isLoading,
            onPressed: _isLoading ? null : _submit,
          ),
        ],
      ),
    );
  }

  Widget _buildSuccessState() {
    return Column(
      children: [
        Container(
          width: 80,
          height: 80,
          decoration: BoxDecoration(
            color: AppTheme.success.withValues(alpha: 0.1),
            shape: BoxShape.circle,
          ),
          child: const Icon(
            Icons.check_circle_outline,
            size: 48,
            color: AppTheme.success,
          ),
        ),
        const SizedBox(height: 24),
        Text(
          'Email Terkirim!',
          style: AuthStyles.titleStyle.copyWith(fontSize: 28),
          textAlign: TextAlign.center,
        ),
        const SizedBox(height: 12),
        Text(
          'Kami telah mengirim link reset password ke',
          style: AuthStyles.subtitleStyle,
          textAlign: TextAlign.center,
        ),
        const SizedBox(height: 4),
        Text(
          _emailController.text,
          style: const TextStyle(
            fontSize: 14,
            fontWeight: FontWeight.w600,
            color: AppTheme.primary,
          ),
          textAlign: TextAlign.center,
        ),
        const SizedBox(height: AuthStyles.buttonSpacing),
        AuthButton(
          text: 'Kembali ke Login',
          onPressed: () => context.go('/shop/login'),
        ),
      ],
    );
  }
}
