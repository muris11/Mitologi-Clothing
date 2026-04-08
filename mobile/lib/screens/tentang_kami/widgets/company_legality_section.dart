import 'package:flutter/material.dart';
import '../../../config/theme.dart';
import '../../../models/tentang_kami/site_settings_model.dart';

class CompanyLegalitySection extends StatelessWidget {
  final LegalityInfo? legality;
  final Map<String, dynamic>? fallback; // About section as fallback

  const CompanyLegalitySection({super.key, this.legality, this.fallback});

  @override
  Widget build(BuildContext context) {
    // Build items list from available data
    final items =
        [
              {
                'label': 'Nama Badan Usaha',
                'value':
                    legality?.legalCompanyName ??
                    fallback?['legal_company_name'],
              },
              {
                'label': 'Alamat Resmi',
                'value': legality?.legalAddress ?? fallback?['legal_address'],
              },
              {
                'label': 'Bidang Usaha',
                'value':
                    legality?.legalBusinessField ??
                    fallback?['legal_business_field'],
              },
              {
                'label': 'NPWP',
                'value': legality?.legalNpwp ?? fallback?['legal_npwp'],
              },
              {
                'label': 'NIB',
                'value': legality?.legalNib ?? fallback?['legal_nib'],
              },
              {
                'label': 'NMID',
                'value': legality?.legalNmid ?? fallback?['legal_nmid'],
              },
            ]
            .where(
              (item) =>
                  item['value'] != null && item['value'].toString().isNotEmpty,
            )
            .toList();

    if (items.isEmpty) return const SizedBox.shrink();

    return Container(
      color: Colors.white,
      padding: const EdgeInsets.symmetric(vertical: 40, horizontal: 24),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // Header with icon
          Row(
            children: [
              Container(
                padding: const EdgeInsets.all(12),
                decoration: BoxDecoration(
                  color: AppTheme.primary.withValues(alpha: 0.05),
                  borderRadius: BorderRadius.circular(16),
                  border: Border.all(
                    color: AppTheme.primary.withValues(alpha: 0.1),
                  ),
                ),
                child: Icon(
                  Icons.account_balance_outlined,
                  size: 28,
                  color: AppTheme.primary,
                ),
              ),
              const SizedBox(width: 16),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    const Text(
                      'INFORMASI LEGAL',
                      style: TextStyle(
                        fontSize: 11,
                        fontWeight: FontWeight.w800,
                        color: AppTheme.accent,
                        letterSpacing: 0.5,
                      ),
                    ),
                    const SizedBox(height: 4),
                    const Text(
                      'Legalitas Perusahaan',
                      style: TextStyle(
                        fontSize: 22,
                        fontWeight: FontWeight.w900,
                        color: AppTheme.primary,
                      ),
                    ),
                  ],
                ),
              ),
            ],
          ),
          const SizedBox(height: 24),

          // Legal items grid
          GridView.builder(
            shrinkWrap: true,
            physics: const NeverScrollableScrollPhysics(),
            gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
              crossAxisCount: 2,
              crossAxisSpacing: 12,
              mainAxisSpacing: 12,
              childAspectRatio: 1.5,
            ),
            itemCount: items.length,
            itemBuilder: (context, index) {
              final item = items[index];
              return Container(
                padding: const EdgeInsets.all(16),
                decoration: BoxDecoration(
                  color: const Color(0xFFF8FAFC), // slate-50
                  borderRadius: BorderRadius.circular(16),
                  border: Border.all(color: AppTheme.muted),
                ),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    Text(
                      item['label'] as String,
                      style: TextStyle(
                        fontSize: 10,
                        fontWeight: FontWeight.w800,
                        color: AppTheme.onSurfaceMuted,
                        letterSpacing: 0.5,
                      ),
                    ),
                    const SizedBox(height: 8),
                    Text(
                      item['value'] as String,
                      style: const TextStyle(
                        fontSize: 14,
                        fontWeight: FontWeight.w800,
                        color: AppTheme.primary,
                      ),
                      maxLines: 2,
                      overflow: TextOverflow.ellipsis,
                    ),
                  ],
                ),
              );
            },
          ),
        ],
      ),
    );
  }
}
