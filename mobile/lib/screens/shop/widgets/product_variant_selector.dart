import '../../../widgets/animations/animations.dart';

class ProductVariantSelector extends StatelessWidget {
  final Product product;
  final Map<String, String> selectedOptions;
  final Function(String, String) onVariantSelected;

  const ProductVariantSelector({
    super.key,
    required this.product,
    required this.selectedOptions,
    required this.onVariantSelected,
  });

  @override
  Widget build(BuildContext context) {
    if (product.options.isEmpty) return const SizedBox.shrink();

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: product.options.map((option) {
        return Padding(
          padding: const EdgeInsets.only(bottom: 24),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Text(
                    option.name,
                    style: Theme.of(context).textTheme.titleSmall?.copyWith(
                          fontWeight: FontWeight.w900,
                          letterSpacing: 0.5,
                          color: AppTheme.onSurface,
                        ),
                  ),
                  if (selectedOptions.containsKey(option.name))
                    FadeIn(
                      child: Text(
                        selectedOptions[option.name]!,
                        style: Theme.of(context).textTheme.labelMedium?.copyWith(
                              color: AppTheme.primary,
                              fontWeight: FontWeight.w800,
                            ),
                      ),
                    ),
                ],
              ),
              const SizedBox(height: 12),
              Wrap(
                spacing: 12,
                runSpacing: 12,
                children: option.values.map((value) {
                  final isSelected = selectedOptions[option.name] == value;

                  return GestureDetector(
                    onTap: () => onVariantSelected(option.name, value),
                    child: AnimatedContainer(
                      duration: const Duration(milliseconds: 250),
                      curve: Curves.easeOutBack,
                      padding: const EdgeInsets.symmetric(
                        horizontal: 20,
                        vertical: 12,
                      ),
                      decoration: BoxDecoration(
                        color: isSelected ? AppTheme.primary : Colors.white,
                        borderRadius: BorderRadius.circular(14),
                        border: Border.all(
                          color: isSelected 
                              ? AppTheme.primary 
                              : AppTheme.outlineVariant,
                          width: 1.5,
                        ),
                        boxShadow: isSelected ? AppTheme.shadowMedium : null,
                      ),
                      child: AnimatedDefaultTextStyle(
                        duration: const Duration(milliseconds: 200),
                        style: TextStyle(
                          color: isSelected ? Colors.white : AppTheme.onSurface,
                          fontWeight: isSelected ? FontWeight.w800 : FontWeight.w600,
                          fontSize: 13,
                        ),
                        child: Text(value),
                      ),
                    ),
                  );
                }).toList(),
              ),
            ],
          ),
        );
      }).toList(),
    );
  }
}
