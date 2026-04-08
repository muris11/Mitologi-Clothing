import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../../../utils/navigation_helper.dart';
import '../../providers/profile_provider.dart';
import '../../config/theme.dart';
import '../../utils/responsive_helper.dart';
import '../../widgets/common/mitologi_scaffold.dart';
import '../../widgets/skeleton/skeleton.dart';
import '../../widgets/animations/blur_fade.dart';

class ChangePasswordScreen extends StatefulWidget {
  const ChangePasswordScreen({super.key});

  @override
  State<ChangePasswordScreen> createState() => _ChangePasswordScreenState();
}

class _ChangePasswordScreenState extends State<ChangePasswordScreen> {
  final _formKey = GlobalKey<FormState>();

  final _currentPasswordController = TextEditingController();
  final _newPasswordController = TextEditingController();
  final _confirmPasswordController = TextEditingController();

  bool _obscureCurrent = true;
  bool _obscureNew = true;
  bool _obscureConfirm = true;

  @override
  void dispose() {
    _currentPasswordController.dispose();
    _newPasswordController.dispose();
    _confirmPasswordController.dispose();
    super.dispose();
  }

  void _changePassword() async {
    if (_formKey.currentState!.validate()) {
      if (_newPasswordController.text != _confirmPasswordController.text) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(
            content: Text('Password baru dan konfirmasi tidak cocok'),
          ),
        );
        return;
      }

      final success = await context.read<ProfileProvider>().changePassword(
        currentPassword: _currentPasswordController.text,
        newPassword: _newPasswordController.text,
        confirmPassword: _confirmPasswordController.text,
      );

      if (success && mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Password berhasil diubah')),
        );
        context.popOrGoHome();
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    final provider = context.watch<ProfileProvider>();

    return MitologiScaffold(
      title: 'Ubah Password',
      subtitle: 'Perbarui password untuk keamanan akun Anda',
      showLogo: false,
      bodyPadding: EdgeInsets.zero,
      body: provider.isLoading
          ? const ChangePasswordSkeleton()
          : SingleChildScrollView(
              padding: EdgeInsets.all(
                ResponsiveHelper.horizontalPadding(context),
              ),
              child: FadeInUp(
                child: Form(
                  key: _formKey,
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      if (provider.error != null)
                        Container(
                          padding: EdgeInsets.all(
                            ResponsiveHelper.horizontalPadding(context),
                          ),
                          margin: const EdgeInsets.only(bottom: 24),
                          decoration: BoxDecoration(
                            color: AppTheme.error.withValues(alpha: 0.1),
                            borderRadius: AppTheme.radius16,
                          ),
                          child: Row(
                            children: [
                              const Icon(
                                Icons.error_outline,
                                color: AppTheme.error,
                              ),
                              const SizedBox(width: 12),
                              Expanded(
                                child: Text(
                                  provider.error!,
                                  style: const TextStyle(color: AppTheme.error),
                                ),
                              ),
                            ],
                          ),
                        ),

                      _buildPasswordField(
                        label: 'Password Saat Ini',
                        controller: _currentPasswordController,
                        obscureText: _obscureCurrent,
                        onToggleObscure: () =>
                            setState(() => _obscureCurrent = !_obscureCurrent),
                      ),
                      const SizedBox(height: 24),
                      _buildPasswordField(
                        label: 'Password Baru',
                        controller: _newPasswordController,
                        obscureText: _obscureNew,
                        onToggleObscure: () =>
                            setState(() => _obscureNew = !_obscureNew),
                      ),
                      const SizedBox(height: 24),
                      _buildPasswordField(
                        label: 'Konfirmasi Password Baru',
                        controller: _confirmPasswordController,
                        obscureText: _obscureConfirm,
                        onToggleObscure: () =>
                            setState(() => _obscureConfirm = !_obscureConfirm),
                      ),
                      const SizedBox(height: 48),

                      SizedBox(
                        width: double.infinity,
                        height: 56,
                        child: ElevatedButton(
                          onPressed: _changePassword,
                          style: ElevatedButton.styleFrom(
                            backgroundColor: AppTheme.accent,
                            elevation: 0,
                            foregroundColor: Colors.white,
                            padding: const EdgeInsets.symmetric(vertical: 16),
                            shape: RoundedRectangleBorder(
                              borderRadius: AppTheme.radius16,
                            ),
                          ),
                          child: const Text(
                            'Simpan Password',
                            style: TextStyle(
                              fontSize: 16,
                              fontWeight: FontWeight.w700,
                            ),
                          ),
                        ),
                      ),
                    ],
                  ),
                ),
              ),
            ),
    );
  }

  Widget _buildPasswordField({
    required String label,
    required TextEditingController controller,
    required bool obscureText,
    required VoidCallback onToggleObscure,
  }) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          label,
          style: const TextStyle(
            fontWeight: FontWeight.w600,
            color: AppTheme.onSurfaceVariant,
            fontSize: 14,
          ),
        ),
        const SizedBox(height: 8),
        TextFormField(
          controller: controller,
          obscureText: obscureText,
          decoration: InputDecoration(
            hintText: 'Masukkan $label',
            border: OutlineInputBorder(
              borderRadius: BorderRadius.circular(12),
              borderSide: BorderSide.none,
            ),
            enabledBorder: OutlineInputBorder(
              borderRadius: BorderRadius.circular(12),
              borderSide: BorderSide.none,
            ),
            focusedBorder: OutlineInputBorder(
              borderRadius: BorderRadius.circular(12),
              borderSide: const BorderSide(color: AppTheme.primary, width: 2),
            ),
            filled: true,
            fillColor: AppTheme.surfaceContainerLow,
            suffixIcon: IconButton(
              icon: Icon(
                obscureText
                    ? Icons.visibility_off_outlined
                    : Icons.visibility_outlined,
                color: AppTheme.onSurfaceMuted,
              ),
              tooltip: obscureText ? 'Show password' : 'Hide password',
              onPressed: onToggleObscure,
            ),
          ),
          validator: (value) {
            if (value == null || value.isEmpty) {
              return '$label wajib diisi';
            }
            if (value.length < 8 && label != 'Password Saat Ini') {
              return 'Password minimal 8 karakter';
            }
            return null;
          },
        ),
      ],
    );
  }
}
