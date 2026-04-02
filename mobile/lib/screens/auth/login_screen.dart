import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:go_router/go_router.dart';
import 'package:provider/provider.dart';

import '../../config/theme.dart';
import '../../config/auth_styles.dart';
import '../../widgets/common/auth_page_shell.dart';
import '../../widgets/auth/auth_text_field.dart';
import '../../widgets/auth/auth_button.dart';
import '../../providers/auth_provider.dart';

class LoginScreen extends StatefulWidget {
  const LoginScreen({super.key});

  @override
  State<LoginScreen> createState() => _LoginScreenState();
}

class _LoginScreenState extends State<LoginScreen>
    with SingleTickerProviderStateMixin {
  final _emailController = TextEditingController();
  final _passwordController = TextEditingController();
  final _formKey = GlobalKey<FormState>();
  bool _obscurePassword = true;

  late AnimationController _animController;
  late List<Animation<double>> _fieldAnimations;

  @override
  void initState() {
    super.initState();
    _animController = AnimationController(
      vsync: this,
      duration: const Duration(milliseconds: 600),
    );

    _fieldAnimations = List.generate(4, (index) {
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
    _emailController.dispose();
    _passwordController.dispose();
    super.dispose();
  }

  void _submit() async {
    if (!_formKey.currentState!.validate()) return;

    FocusScope.of(context).unfocus();

    final authProvider = Provider.of<AuthProvider>(context, listen: false);
    final success = await authProvider.login(
      _emailController.text.trim(),
      _passwordController.text,
    );

    if (success && mounted) {
      context.go('/');
    } else if (mounted) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text(authProvider.error ?? 'Login gagal'),
          backgroundColor: AppTheme.error,
          behavior: SnackBarBehavior.floating,
          shape: RoundedRectangleBorder(borderRadius: AppTheme.radius16),
        ),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return AnnotatedRegion<SystemUiOverlayStyle>(
      value: SystemUiOverlayStyle.dark,
      child: AuthPageShell(
        title: 'Selamat Datang Kembali',
        subtitle: 'Masuk untuk melanjutkan belanja dan mengelola pesanan Anda.',
        child: Form(
          key: _formKey,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.stretch,
            children: [
              _buildAnimatedField(
                index: 0,
                child: AuthTextField(
                  label: 'Email',
                  hintText: 'Masukkan email Anda',
                  prefixIcon: Icons.email_outlined,
                  controller: _emailController,
                  keyboardType: TextInputType.emailAddress,
                  validator: (value) {
                    if (value == null ||
                        value.isEmpty ||
                        !value.contains('@')) {
                      return 'Masukkan email yang valid';
                    }
                    return null;
                  },
                ),
              ),
              const SizedBox(height: AuthStyles.fieldSpacing),
              _buildAnimatedField(
                index: 1,
                child: AuthTextField(
                  label: 'Password',
                  hintText: 'Masukkan password Anda',
                  prefixIcon: Icons.lock_outline,
                  controller: _passwordController,
                  obscureText: _obscurePassword,
                  suffixIcon: IconButton(
                    tooltip: _obscurePassword
                        ? 'Show password'
                        : 'Hide password',
                    icon: Icon(
                      _obscurePassword
                          ? Icons.visibility_off_outlined
                          : Icons.visibility_outlined,
                      size: 20,
                      color: AppTheme.onSurfaceVariant,
                    ),
                    onPressed: () {
                      setState(() {
                        _obscurePassword = !_obscurePassword;
                      });
                    },
                  ),
                  validator: (value) {
                    if (value == null || value.isEmpty || value.length < 6) {
                      return 'Password minimal 6 karakter';
                    }
                    return null;
                  },
                ),
              ),
              const SizedBox(height: 8),
              _buildAnimatedField(
                index: 2,
                child: Align(
                  alignment: Alignment.centerRight,
                  child: TextButton(
                    onPressed: () => context.push('/shop/forgot-password'),
                    child: Text(
                      'Lupa Password?',
                      style: TextStyle(
                        color: AppTheme.accent,
                        fontWeight: FontWeight.w600,
                        fontSize: 14,
                      ),
                    ),
                  ),
                ),
              ),
              const SizedBox(height: AuthStyles.buttonSpacing),
              _buildAnimatedField(
                index: 3,
                child: Consumer<AuthProvider>(
                  builder: (context, auth, _) {
                    return AuthButton(
                      text: 'Masuk',
                      isLoading: auth.isLoading,
                      onPressed: auth.isLoading ? null : _submit,
                    );
                  },
                ),
              ),
              const SizedBox(height: 24),
              _buildAnimatedField(
                index: 3,
                child: Row(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    Text(
                      'Belum punya akun?',
                      style: TextStyle(
                        color: AppTheme.onSurfaceVariant,
                        fontSize: 14,
                      ),
                    ),
                    TextButton(
                      onPressed: () => context.push('/shop/register'),
                      child: Text(
                        'Daftar',
                        style: TextStyle(
                          color: AppTheme.accent,
                          fontWeight: FontWeight.w700,
                          fontSize: 14,
                        ),
                      ),
                    ),
                  ],
                ),
              ),
            ],
          ),
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
