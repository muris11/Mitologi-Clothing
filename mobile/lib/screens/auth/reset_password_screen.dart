import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import 'package:provider/provider.dart';

import '../../config/theme.dart';
import '../../config/auth_styles.dart';
import '../../providers/auth_provider.dart';
import '../../widgets/common/auth_page_shell.dart';
import '../../widgets/auth/auth_text_field.dart';
import '../../widgets/auth/auth_button.dart';

class ResetPasswordScreen extends StatefulWidget {
  final String? token;
  final String? email;

  const ResetPasswordScreen({super.key, this.token, this.email});

  @override
  State<ResetPasswordScreen> createState() => _ResetPasswordScreenState();
}

class _ResetPasswordScreenState extends State<ResetPasswordScreen>
    with SingleTickerProviderStateMixin {
  final _passwordController = TextEditingController();
  final _confirmPasswordController = TextEditingController();
  final _formKey = GlobalKey<FormState>();
  bool _obscurePassword = true;
  bool _obscureConfirmPassword = true;

  late AnimationController _animController;
  late List<Animation<double>> _fieldAnimations;

  @override
  void initState() {
    super.initState();
    _animController = AnimationController(
      vsync: this,
      duration: const Duration(milliseconds: 600),
    );

    _fieldAnimations = List.generate(3, (index) {
      return Tween<double>(begin: 0.0, end: 1.0).animate(
        CurvedAnimation(
          parent: _animController,
          curve: Interval(
            index * 0.1,
            0.6 + (index * 0.1),
            curve: Curves.easeOut,
          ),
        ),
      );
    });

    _animController.forward();
  }

  @override
  void dispose() {
    _animController.dispose();
    _passwordController.dispose();
    _confirmPasswordController.dispose();
    super.dispose();
  }

  void _submit() async {
    if (!_formKey.currentState!.validate()) return;

    final token = widget.token;
    final email = widget.email;

    if (token == null || email == null) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('Token atau email tidak valid. Silakan coba lagi.'),
          backgroundColor: AppTheme.error,
          behavior: SnackBarBehavior.floating,
        ),
      );
      return;
    }

    FocusScope.of(context).unfocus();

    final authProvider = Provider.of<AuthProvider>(context, listen: false);
    final success = await authProvider.resetPassword(
      email: email,
      token: token,
      password: _passwordController.text,
      passwordConfirmation: _confirmPasswordController.text,
    );

    if (success && mounted) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('Password berhasil diatur ulang. Silakan login.'),
          backgroundColor: AppTheme.success,
          behavior: SnackBarBehavior.floating,
        ),
      );
      context.go('/shop/login');
    } else if (mounted) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text(authProvider.error ?? 'Gagal mengatur ulang password'),
          backgroundColor: AppTheme.error,
          behavior: SnackBarBehavior.floating,
        ),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return AuthPageShell(
      title: 'Buat Password Baru',
      subtitle: 'Masukkan password baru untuk akun Anda.',
      child: Form(
        key: _formKey,
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            _buildAnimatedField(
              index: 0,
              child: AuthTextField(
                label: 'Password Baru',
                hintText: 'Minimal 8 karakter',
                prefixIcon: Icons.lock_outline,
                controller: _passwordController,
                obscureText: _obscurePassword,
                suffixIcon: IconButton(
                  tooltip: _obscurePassword ? 'Show password' : 'Hide password',
                  icon: Icon(
                    _obscurePassword ? Icons.visibility_off : Icons.visibility,
                    size: 20,
                    color: AppTheme.onSurfaceVariant,
                  ),
                  onPressed: () =>
                      setState(() => _obscurePassword = !_obscurePassword),
                ),
                validator: (value) {
                  if (value == null || value.isEmpty || value.length < 8) {
                    return 'Password minimal 8 karakter';
                  }
                  return null;
                },
              ),
            ),
            const SizedBox(height: AuthStyles.fieldSpacing),
            _buildAnimatedField(
              index: 1,
              child: AuthTextField(
                label: 'Konfirmasi Password',
                hintText: 'Masukkan ulang password',
                prefixIcon: Icons.lock_outline,
                controller: _confirmPasswordController,
                obscureText: _obscureConfirmPassword,
                suffixIcon: IconButton(
                  tooltip: _obscureConfirmPassword
                      ? 'Show password'
                      : 'Hide password',
                  icon: Icon(
                    _obscureConfirmPassword
                        ? Icons.visibility_off
                        : Icons.visibility,
                    size: 20,
                    color: AppTheme.onSurfaceVariant,
                  ),
                  onPressed: () => setState(
                    () => _obscureConfirmPassword = !_obscureConfirmPassword,
                  ),
                ),
                validator: (value) {
                  if (value != _passwordController.text) {
                    return 'Password tidak cocok';
                  }
                  return null;
                },
              ),
            ),
            const SizedBox(height: AuthStyles.buttonSpacing),
            _buildAnimatedField(
              index: 2,
              child: Consumer<AuthProvider>(
                builder: (context, auth, _) {
                  return AuthButton(
                    text: 'Simpan Password',
                    isLoading: auth.isLoading,
                    onPressed: auth.isLoading ? null : _submit,
                  );
                },
              ),
            ),
            const SizedBox(height: 16),
            _buildAnimatedField(
              index: 2,
              child: Center(
                child: TextButton(
                  onPressed: () => context.go('/shop/login'),
                  child: Text(
                    'Kembali ke Login',
                    style: TextStyle(
                      color: AppTheme.accent,
                      fontWeight: FontWeight.w600,
                      fontSize: 14,
                    ),
                  ),
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildAnimatedField({required int index, required Widget child}) {
    return AnimatedBuilder(
      animation: _fieldAnimations[index],
      builder: (context, _) {
        return Opacity(
          opacity: _fieldAnimations[index].value,
          child: Transform.translate(
            offset: Offset(0, 16 * (1 - _fieldAnimations[index].value)),
            child: child,
          ),
        );
      },
    );
  }
}
