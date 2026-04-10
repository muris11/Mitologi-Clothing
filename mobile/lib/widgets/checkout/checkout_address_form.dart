import 'package:flutter/material.dart';
import '../../config/theme.dart';
import '../../utils/responsive_helper.dart';
import '../animations/animations.dart';

class CheckoutAddressForm extends StatelessWidget {
  final GlobalKey<FormState> formKey;
  final Function(String) onFirstNameSaved;
  final Function(String) onLastNameSaved;
  final Function(String) onPhoneSaved;
  final Function(String) onAddressSaved;
  final Function(String) onCitySaved;
  final Function(String) onProvinceSaved;
  final Function(String) onZipSaved;

  const CheckoutAddressForm({
    super.key,
    required this.formKey,
    required this.onFirstNameSaved,
    required this.onLastNameSaved,
    required this.onPhoneSaved,
    required this.onAddressSaved,
    required this.onCitySaved,
    required this.onProvinceSaved,
    required this.onZipSaved,
  });

  InputDecoration _inputDecoration(
    BuildContext context, 
    String label, 
    IconData icon
  ) {
    return InputDecoration(
      labelText: label,
      prefixIcon: Icon(icon, size: 20, color: AppTheme.onSurfaceVariant),
      labelStyle: const TextStyle(
        color: AppTheme.onSurfaceVariant,
        fontSize: 14,
        fontWeight: FontWeight.w500,
      ),
      filled: true,
      fillColor: AppTheme.surfaceContainerLow,
      border: OutlineInputBorder(
        borderRadius: AppTheme.radius16,
        borderSide: BorderSide.none,
      ),
      enabledBorder: OutlineInputBorder(
        borderRadius: AppTheme.radius16,
        borderSide: BorderSide.none,
      ),
      focusedBorder: OutlineInputBorder(
        borderRadius: AppTheme.radius16,
        borderSide: const BorderSide(color: AppTheme.primary, width: 2),
      ),
      errorBorder: OutlineInputBorder(
        borderRadius: AppTheme.radius16,
        borderSide: const BorderSide(color: AppTheme.error, width: 1),
      ),
      contentPadding: EdgeInsets.symmetric(
        horizontal: ResponsiveHelper.horizontalPadding(context),
        vertical: 18,
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Form(
      key: formKey,
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          FadeInUp(
            delay: const Duration(milliseconds: 100),
            child: const Padding(
              padding: EdgeInsets.only(left: 4, bottom: 16),
              child: Text(
                'Informasi Penerima',
                style: TextStyle(
                  fontWeight: FontWeight.w800,
                  fontSize: 16,
                  color: AppTheme.onSurface,
                ),
              ),
            ),
          ),
          FadeInUp(
            delay: const Duration(milliseconds: 200),
            child: Row(
              children: [
                Expanded(
                  child: TextFormField(
                    decoration: _inputDecoration(context, 'Nama Depan', Icons.person_outline),
                    validator: (v) =>
                        v == null || v.isEmpty ? 'Masukkan nama depan' : null,
                    onSaved: (v) => onFirstNameSaved(v ?? ''),
                  ),
                ),
                const SizedBox(width: 16),
                Expanded(
                  child: TextFormField(
                    decoration: _inputDecoration(context, 'Nama Belakang', Icons.people_outline),
                    validator: (v) =>
                        v == null || v.isEmpty ? 'Masukkan nama belakang' : null,
                    onSaved: (v) => onLastNameSaved(v ?? ''),
                  ),
                ),
              ],
            ),
          ),
          const SizedBox(height: 16),
          FadeInUp(
            delay: const Duration(milliseconds: 300),
            child: TextFormField(
              decoration: _inputDecoration(context, 'No. WhatsApp / HP', Icons.phone_android_outlined),
              keyboardType: TextInputType.phone,
              validator: (v) =>
                  v == null || v.isEmpty ? 'Masukkan nomor WhatsApp' : null,
              onSaved: (v) => onPhoneSaved(v ?? ''),
            ),
          ),
          const SizedBox(height: 32),
          FadeInUp(
            delay: const Duration(milliseconds: 400),
            child: const Padding(
              padding: EdgeInsets.only(left: 4, bottom: 16),
              child: Text(
                'Alamat Pengiriman',
                style: TextStyle(
                  fontWeight: FontWeight.w800,
                  fontSize: 16,
                  color: AppTheme.onSurface,
                ),
              ),
            ),
          ),
          FadeInUp(
            delay: const Duration(milliseconds: 500),
            child: TextFormField(
              decoration: _inputDecoration(context, 'Alamat Lengkap', Icons.map_outlined),
              maxLines: 2,
              validator: (v) =>
                  v == null || v.isEmpty ? 'Masukkan alamat lengkap' : null,
              onSaved: (v) => onAddressSaved(v ?? ''),
            ),
          ),
          const SizedBox(height: 16),
          FadeInUp(
            delay: const Duration(milliseconds: 600),
            child: Row(
              children: [
                Expanded(
                  child: TextFormField(
                    decoration: _inputDecoration(context, 'Kota/Kabupaten', Icons.location_city_outlined),
                    validator: (v) => 
                        v == null || v.isEmpty ? 'Masukkan kota' : null,
                    onSaved: (v) => onCitySaved(v ?? ''),
                  ),
                ),
                const SizedBox(width: 16),
                Expanded(
                  child: TextFormField(
                    decoration: _inputDecoration(context, 'Provinsi', Icons.explore_outlined),
                    validator: (v) =>
                        v == null || v.isEmpty ? 'Masukkan provinsi' : null,
                    onSaved: (v) => onProvinceSaved(v ?? ''),
                  ),
                ),
              ],
            ),
          ),
          const SizedBox(height: 16),
          FadeInUp(
            delay: const Duration(milliseconds: 700),
            child: TextFormField(
              decoration: _inputDecoration(context, 'Kode Pos', Icons.local_post_office_outlined),
              keyboardType: TextInputType.number,
              validator: (v) => 
                  v == null || v.isEmpty ? 'Masukkan kode pos' : null,
              onSaved: (v) => onZipSaved(v ?? ''),
            ),
          ),
        ],
      ),
    );
  }
}
