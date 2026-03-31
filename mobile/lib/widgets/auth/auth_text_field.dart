import 'package:flutter/material.dart';
import '../../config/theme.dart';
import '../../config/auth_styles.dart';

class AuthTextField extends StatelessWidget {
  final String label;
  final IconData? prefixIcon;
  final Widget? suffixIcon;
  final bool obscureText;
  final TextEditingController? controller;
  final String? Function(String?)? validator;
  final TextInputType? keyboardType;
  final String? hintText;
  final void Function(String)? onSubmitted;
  final FocusNode? focusNode;
  final bool enabled;
  final int maxLines;

  const AuthTextField({
    super.key,
    required this.label,
    this.prefixIcon,
    this.suffixIcon,
    this.obscureText = false,
    this.controller,
    this.validator,
    this.keyboardType,
    this.hintText,
    this.onSubmitted,
    this.focusNode,
    this.enabled = true,
    this.maxLines = 1,
  });

  @override
  Widget build(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(label.toUpperCase(), style: AuthStyles.labelStyle),
        const SizedBox(height: 8),
        TextFormField(
          controller: controller,
          obscureText: obscureText,
          validator: validator,
          keyboardType: keyboardType,
          onFieldSubmitted: onSubmitted,
          focusNode: focusNode,
          enabled: enabled,
          maxLines: maxLines,
          style: AuthStyles.inputStyle,
          decoration: InputDecoration(
            hintText: hintText,
            hintStyle: TextStyle(
              color: AppTheme.onSurfaceVariant.withValues(alpha: 0.7),
              fontSize: AuthStyles.inputSize,
            ),
            filled: true,
            fillColor: AppTheme.cream,
            prefixIcon: prefixIcon != null
                ? Icon(prefixIcon, size: 20, color: AppTheme.onSurfaceVariant)
                : null,
            suffixIcon: suffixIcon,
            contentPadding: const EdgeInsets.symmetric(
              horizontal: 16,
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
              borderSide: const BorderSide(color: AppTheme.primary, width: 1.5),
            ),
            errorBorder: OutlineInputBorder(
              borderRadius: BorderRadius.circular(AuthStyles.inputRadius),
              borderSide: const BorderSide(color: AppTheme.error),
            ),
            focusedErrorBorder: OutlineInputBorder(
              borderRadius: BorderRadius.circular(AuthStyles.inputRadius),
              borderSide: const BorderSide(color: AppTheme.error, width: 1.5),
            ),
          ),
        ),
      ],
    );
  }
}
